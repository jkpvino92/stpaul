<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Builder;

class NotificationMessageBuilder implements BuilderInterface
{
    const SUCCESS_STATUS = 1;

    /**
     * @inheritdoc
     */
    public function build(array $params)
    {
        $result = '';

        if (isset($params['status'])) {
            $status = (int)$params['status'];

            if ($status === self::SUCCESS_STATUS) {
                $result = __('Notification has been sent.');
            } else {
                $result = __('Notification send error.');
            }
        }

        return $result;
    }
}
