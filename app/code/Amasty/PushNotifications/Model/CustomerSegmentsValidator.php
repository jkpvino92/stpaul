<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model;

use Amasty\PushNotifications\Exception\NotificationException;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class will be used as proxy in CampaignProcessor
 * due to operating with Amasty Customer Segments module
 * which can be not installed
 */
class CustomerSegmentsValidator
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        SubscriberRepository $subscriberRepository,
        ObjectManagerInterface $objectManager
    ) {
        $this->subscriberRepository = $subscriberRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @param array $subscriberToken
     * @param CampaignSegments[] $segments
     *
     * @throws NotificationException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function validateSegments(&$subscriberToken, $segments)
    {
        if (empty($segments)) {
            return;
        }

        foreach ($subscriberToken as $key => $token) {
            $subscriber = $this->subscriberRepository->getByToken($token);

            if ($id = (int)$subscriber->getCustomerId()) {
                foreach ($segments as $segment) {
                    $validCustomer = false;
                    $customersInSegment = $this->objectManager
                        ->create(\Amasty\Segments\Model\ResourceModel\Index::class)
                        ->getIdsFromIndex('customer_id', $segment->getSegmentId());

                    foreach ($customersInSegment as $customer) {
                        if ($customer['customer_id'] == $id) {
                            $validCustomer = true;
                            break;
                        }
                    }

                    if (!$validCustomer) {
                        unset($subscriberToken[$key]);
                    } else {
                        break;
                    }
                }
            } else {
                unset($subscriberToken[$key]);
            }
        }
    }
}
