<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Ui\DataProvider\Listing;

use Amasty\PushNotifications\Model\ResourceModel\Campaign\CollectionFactory;
use Amasty\PushNotifications\Api\CampaignRepositoryInterface;

class CampaignDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        CampaignRepositoryInterface $repository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->repository = $repository;
    }

    /**
     * Get data
     *
     * @return array
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();
        foreach ($data['items'] as $key => $campaign) {
            $campaign = $this->repository->getById($campaign['campaign_id']);
            $campaignData = $campaign->getData();
            $campaignData['store_id'] = $campaign->getStoreIds();
            $data['items'][$key] = $campaignData;
        }

        return $data;
    }
}
