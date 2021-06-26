<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\Builder;

use Amasty\PushNotifications\Api\Data\CampaignInterface;
use Amasty\PushNotifications\Model\ConfigProvider;
use Amasty\PushNotifications\Model\FileUploader\FileInfoCollector;

class LogoUrlBuilder implements BuilderInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var FileInfoCollector
     */
    private $fileInfoCollector;

    public function __construct(
        ConfigProvider $configProvider,
        FileInfoCollector $fileInfoCollector
    ) {
        $this->configProvider = $configProvider;
        $this->fileInfoCollector = $fileInfoCollector;
    }

    /**
     * @inheritdoc
     */
    public function build(array $params)
    {
        if ($params[CampaignInterface::IS_DEFAULT_LOGO]) {
            return $this->getDefaultLogoUrl();
        }

        return $this->getUrlFromFileInfo(
            $this->fileInfoCollector->getInfoByFilePath($params[CampaignInterface::LOGO_PATH])
        );
    }

    /**
     * @return string
     */
    public function getDefaultLogoUrl()
    {
        $logoPath = DIRECTORY_SEPARATOR . $this->configProvider->getLogoPath();

        return $this->getUrlFromFileInfo($this->fileInfoCollector->getInfoByFilePath($logoPath));
    }

    /**
     * @param $fileInfo
     * @return string
     */
    private function getUrlFromFileInfo($fileInfo)
    {
        if ($fileInfo) {
            $fileInfo = array_shift($fileInfo);
        }

        return isset($fileInfo['url']) ? $fileInfo['url'] : '';
    }
}
