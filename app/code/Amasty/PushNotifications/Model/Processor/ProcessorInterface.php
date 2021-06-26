<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Processor;

use Amasty\PushNotifications\Exception\NotificationException;

interface ProcessorInterface
{
    /**
     * @param array $params
     * @param int|null $storeId
     *
     * @return array
     *
     * @throws NotificationException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function process(array $params, $storeId = null);
}
