<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Processor;

use Amasty\PushNotifications\Api\Data\CampaignInterface;
use Amasty\PushNotifications\Exception\NotificationException;
use Amasty\PushNotifications\Model\Builder\DateTimeBuilder;
use Amasty\PushNotifications\Model\CampaignRepository;
use Amasty\PushNotifications\Model\SubscriberRepository;
use Amasty\PushNotifications\Model\ResourceModel\Campaign\CollectionFactory as CampaignCollectionFactory;
use Amasty\PushNotifications\Model\ResourceModel\Subscriber\CollectionFactory as SubscriberCollectionFactory;
use Amasty\PushNotifications\Model\OptionSource\Campaign\SegmentationSource;
use Amasty\Base\Ui\Component\Listing\Column\StoreOptions;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CampaignProcessor
{
    /**
     * @var \Amasty\PushNotifications\Model\Processor\NotificationProcessor
     */
    private $notificationProcessor;

    /**
     * @var DateTimeBuilder
     */
    private $dateTimeBuilder;

    /**
     * @var CampaignCollectionFactory
     */
    private $campaignCollectionFactory;

    /**
     * @var SubscriberCollectionFactory
     */
    private $subscriberCollectionFactory;

    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Amasty\PushNotifications\Model\CustomerSegmentsValidator
     */
    private $customerSegmentsValidator;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    public function __construct(
        NotificationProcessor $notificationProcessor,
        DateTimeBuilder $dateTimeBuilder,
        CampaignCollectionFactory $campaignCollectionFactory,
        SubscriberCollectionFactory $subscriberCollectionFactory,
        CampaignRepository $campaignRepository,
        SubscriberRepository $subscriberRepository,
        CustomerRepositoryInterface $customerRepository,
        \Amasty\PushNotifications\Model\CustomerSegmentsValidator $customerSegmentsValidator,
        \Magento\Framework\Module\Manager $moduleManager
    ) {
        $this->notificationProcessor = $notificationProcessor;
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->campaignCollectionFactory = $campaignCollectionFactory;
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->campaignRepository = $campaignRepository;
        $this->subscriberRepository = $subscriberRepository;
        $this->customerRepository = $customerRepository;
        $this->customerSegmentsValidator = $customerSegmentsValidator;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @inheritdoc
     */
    public function process(array $params)
    {
        $campaigns = $this->getValidCampaigns();
        $subscriberTokens = $this->getValidSubscribers();
        $segmentsModuleEnabled = $this->moduleManager->isOutputEnabled('Amasty_Segments');

        if ($campaigns && $subscriberTokens) {
            /** @var \Amasty\PushNotifications\Model\Campaign $campaign */
            foreach ($campaigns as $campaign) {
                $stores = $campaign->getStoreIds();
                $segmentationSource = $campaign->getSegmentationSource();
                $counts = ['notificationCount' => 0, 'successNotificationCount' => 0];
                foreach ($subscriberTokens as $subscriberStore => $subscriberToken) {
                    if (array_search(StoreOptions::ALL_STORE_VIEWS, $stores) !== false
                        || array_search($subscriberStore, $stores) !== false
                    ) {
                        $segmentationSource == SegmentationSource::CUSTOMER_GROUPS || !$segmentsModuleEnabled
                            ? $this->validateCustomerGroups($subscriberToken, $campaign->getCustomerGroups())
                            : $this->customerSegmentsValidator->validateSegments(
                                $subscriberToken,
                                $campaign->getSegments()
                            );

                        if (empty($subscriberToken)) {
                            continue;
                        }
                        $result = $this->processCampaign($campaign, array_values($subscriberToken), $subscriberStore);
                        $counts['notificationCount'] += $result['notificationCount'];
                        $counts['successNotificationCount'] += $result['successNotificationCount'];
                    }
                }
                $campaign->setSentCounter($campaign->getSentCounter() + $counts['notificationCount']);
                $campaign->setShownCounter($campaign->getShownCounter() + $counts['successNotificationCount']);
                $campaign->processCampaign();
            }
        } else {
            throw new NotificationException(__('No valid Campaigns or Subscribers was found.'));
        }

        return $params;
    }

    /**
     * @param array $subscriberToken
     * @param \Amasty\PushNotifications\Model\CampaignCustomerGroup[] $customerGroups
     *
     * @throws NotificationException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function validateCustomerGroups(&$subscriberToken, $customerGroups)
    {
        if (empty($customerGroups)) {
            return;
        }
        $customerGroupIds = [];

        foreach ($customerGroups as $group) {
            $customerGroupIds[] = $group->getGroupId();
        }

        foreach ($subscriberToken as $key => $token) {
            $subscriber = $this->subscriberRepository->getByToken($token);

            if ($id = (int)$subscriber->getCustomerId()) {
                $customer = $this->customerRepository->getById($id);

                if (!in_array($customer->getGroupId(), $customerGroupIds)) {
                    unset($subscriberToken[$key]);
                }
            } elseif (!in_array($id, $customerGroupIds)) {
                unset($subscriberToken[$key]);
            }
        }
    }

    /**
     * @param \Amasty\PushNotifications\Api\Data\CampaignInterface $campaign
     * @param array $subscriberTokens
     * @param int|null $storeId
     *
     * @return array
     *
     * @throws NotificationException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function processCampaign($campaign, $subscriberTokens, $storeId = null)
    {
        return $this->notificationProcessor->processByMultipleTokens(
            $campaign->getCampaignId(),
            $subscriberTokens,
            $storeId
        );
    }

    /**
     * @return \Amasty\PushNotifications\Model\ResourceModel\Campaign\Collection
     */
    private function getCampaignCollection()
    {
        return $this->campaignCollectionFactory->create();
    }

    /**
     * @return \Amasty\PushNotifications\Model\ResourceModel\Subscriber\Collection
     */
    private function getSubscriberCollection()
    {
        return $this->subscriberCollectionFactory->create();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getValidCampaigns()
    {
        $campaignCollection = $this->getCampaignCollection();
        $campaignCollection->addTimeFilter($this->dateTimeBuilder->getCurrentFormatedTime())
            ->addFieldToSelect(CampaignInterface::CAMPAIGN_ID);
        $validCampaigns = [];

        foreach ($campaignCollection->getData() as $campaign) {
            $validCampaigns[] = $this->campaignRepository->getById($campaign[CampaignInterface::CAMPAIGN_ID]);
        }

        return $validCampaigns;
    }

    /**
     * @return array
     */
    private function getValidSubscribers()
    {
        $campaignCollection = $this->getSubscriberCollection();
        $campaignCollection->addActiveFilter();

        if ($campaignCollection->getSize()) {
            $campaignCollection->getTokensGroupedByStore();

            $subscribers = $campaignCollection->getData();
            $data = [];
            foreach ($subscribers as $subscriber) {
                $data[$subscriber['store_id']][] = $subscriber['token'];
            }

            return $data;
        }

        return [];
    }
}
