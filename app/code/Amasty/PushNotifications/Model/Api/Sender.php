<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Api;

use Amasty\PushNotifications\Exception\NotificationException;
use Amasty\PushNotifications\Model\ConfigProvider;
use Amasty\PushNotifications\Model\Curl\Curl;

class Sender implements SenderInterface
{
    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Curl
     */
    private $curl;

    public function __construct(
        ConfigProvider $configProvider,
        Curl $curl
    ) {
        $this->configProvider = $configProvider;
        $this->curl = $curl;
    }

    /**
     * @inheritdoc
     */
    public function send(array $params, $storeId = null)
    {
        $response = [];
        $curlBody = $this->curlSend($params, $storeId);

        try {
            if ($curlBody) {
                $response = \Zend_Json_Decoder::decode($curlBody);
            }
        } catch (\Zend_Json_Exception $exception) {
            throw new NotificationException(__($exception->getMessage()));
        }

        return $response;
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    private function getApiKey($storeId = null)
    {
        return $this->configProvider->getFirebaseApiKey($storeId);
    }

    /**
     * @param int|null $storeId
     *
     * @return array
     */
    private function getRequestHeaders($storeId = null)
    {
        return [
            'Content-Type' => $this->contentType,
            'Authorization' => 'key=' . $this->getApiKey($storeId),
        ];
    }

    /**
     * @param $data
     * @return string
     */
    private function prepareRequestBodyToSend($data)
    {
        return \Zend_Json_Encoder::encode($data);
    }

    /**
     * @param array $params
     * @param int|null $storeId
     *
     * @return string
     */
    private function curlSend($params, $storeId = null)
    {
        $this->curl->setHeaders($this->getRequestHeaders($storeId));
        $this->curl->setOption(CURLOPT_FOLLOWLOCATION, 1);
        $this->curl->post($this->configProvider->getFirebaseApiRequestUrl(), $this->prepareRequestBodyToSend($params));

        return $this->curl->getBody();
    }
}
