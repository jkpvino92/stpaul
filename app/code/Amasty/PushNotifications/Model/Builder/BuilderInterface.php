<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Builder;

use Amasty\PushNotifications\Exception\NotificationException;

interface BuilderInterface
{
    /**
     * @param array $params
     * @return array|string
     * @throws NotificationException
     */
    public function build(array $params);
}
