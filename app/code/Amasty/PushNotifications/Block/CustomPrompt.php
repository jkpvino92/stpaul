<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Block;

use Amasty\PushNotifications\Controller\RegistryConstants;
use Amasty\PushNotifications\Model\ConfigProvider;
use Magento\Framework\View\Element\Template;

class CustomPrompt extends Template
{
    const URL_PART_TRIM_CHARACTER = '/';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(
        Template\Context $context,
        ConfigProvider $configProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->request = $context->getRequest();
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getCustomPromptContent()
    {
        return __($this->configProvider->getCustomPromptText());
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getErrorMessage()
    {
        return __('Oops! Notifications are disabled.');
    }

    /**
     * @return array
     */
    public function getJsConfig()
    {
        return [
            'senderId'               => $this->configProvider->getSenderId(),
            'subscribeActionUrl'     => $this->getUrl(RegistryConstants::FIREBASE_SUBSCRIBE_URL_PATH),
            'promptFrequency'        => $this->configProvider->getCustomPromptFrequency(),
            'promptDelay'            => $this->configProvider->getCustomPromptDelay(),
            'maxNotificationsPerDay' => $this->configProvider->getMaxNotificationsPerCustomerDaily(),
            'isEnableModule'         => $this->configProvider->isModuleEnable(),
        ];
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->isCustomPromptAvailable()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * @return bool
     */
    private function isCustomPromptAvailable()
    {
        $availablePages = $this->configProvider->getCustomPromptAvailablePages();

        return $this->configProvider->isCustomPromptEnable()
            && ($this->checkAvailablePage($availablePages) || $this->configProvider->getPromptAvailableOnAllPages());
    }

    /**
     * @param $availablePages
     *
     * @return bool
     */
    private function checkAvailablePage($availablePages)
    {
        if ($availablePages) {
            $currentUrlPath = trim($this->request->getOriginalPathInfo(), self::URL_PART_TRIM_CHARACTER);
            $uri = trim($this->request->getRequestUri(), self::URL_PART_TRIM_CHARACTER);

            foreach ($availablePages as $pattern) {
                switch ($pattern == self::URL_PART_TRIM_CHARACTER) {
                    case 1:
                        if ($currentUrlPath == '') {
                            return true;
                        }

                        break;
                    case 0:
                        $pattern = trim($pattern, self::URL_PART_TRIM_CHARACTER);

                        if (preg_match("|$pattern|", $currentUrlPath) || preg_match("|$pattern|", $uri)) {
                            return true;
                        }

                        break;
                }
            }
        }

        return false;
    }
}
