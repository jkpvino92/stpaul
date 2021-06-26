<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Ui\DataProvider\Form;

use Amasty\PushNotifications\Api\Data\CampaignCustomerGroupInterface;
use Amasty\PushNotifications\Api\Data\CampaignInterface;
use Amasty\PushNotifications\Api\Data\CampaignSegmentsInterface;
use Amasty\PushNotifications\Model\FileUploader\FileInfoCollector;
use Amasty\PushNotifications\Model\ResourceModel\Campaign;
use Amasty\PushNotifications\Model\ResourceModel\Campaign\CollectionFactory;
use Amasty\PushNotifications\Api\CampaignRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Module\Manager;

class CampaignDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Campaign
     */
    private $campaign;

    /**
     * @var FileInfoCollector
     */
    private $fileInfoCollector;

    /**
     * @var Manager
     */
    private $moduleManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        CampaignRepositoryInterface $repository,
        DataPersistorInterface $dataPersistor,
        Campaign $campaign,
        FileInfoCollector $fileInfoCollector,
        Manager $moduleManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
        $this->campaign = $campaign;
        $this->fileInfoCollector = $fileInfoCollector;
        $this->moduleManager = $moduleManager;
    }

    /**
     * Get data
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();

        /**
         * It is need for support of several fieldsets.
         * For details @see \Magento\Ui\Component\Form::getDataSourceData
         */
        if ($data['totalRecords'] > 0) {
            $campaignId = (int)$data['items'][0][CampaignInterface::CAMPAIGN_ID];
            /** @var \Amasty\PushNotifications\Model\Campaign $campaignModel */
            $campaignModel = $this->repository->getById($campaignId);
            $campaignData = $campaignModel->getData();
            $data[$campaignId] = $campaignData;
            $data[$campaignId][CampaignInterface::LOGO_PATH] = $campaignModel->getIsDefaultLogo()
                ? []
                : $this->getLogoData($campaignModel->getLogoPath());
            $stores = [];
            foreach ($campaignModel->getStores() as $store) {
                $stores[] = $store->getStoreId();
            }
            $data[$campaignId]['storeviews'] = implode(',', $stores);

            $campaignGroups = [];
            $customerGroups = $this->repository->getCustomerGroupsByCampaignId($campaignId);

            foreach ($customerGroups as $customerGroup) {
                $campaignGroups[] = $customerGroup[CampaignCustomerGroupInterface::GROUP_ID];
            }
            $data[$campaignId][CampaignInterface::CUSTOMER_GROUPS] = $campaignGroups;

            if ($this->moduleManager->isOutputEnabled('Amasty_Segments')) {
                $campaignSegments = [];
                $segments = $this->repository->getSegmentsByCampaignId($campaignId);

                foreach ($segments as $segment) {
                    $campaignSegments[] = $segment[CampaignSegmentsInterface::SEGMENT_ID];
                }
                $data[$campaignId][CampaignInterface::CUSTOMER_SEGMENTS] = $campaignSegments;
            }
        }

        if ($savedData = $this->dataPersistor->get('campaignData')) {
            $savedCampaignId = isset($savedData['campaign_id']) ? $savedData['campaign_id'] : null;
            if (isset($data[$savedCampaignId])) {
                $data[$savedCampaignId] = array_merge($data[$savedCampaignId], $savedData);
            } else {
                $data[$savedCampaignId] = $savedData;
            }
            $stores = [];
            foreach ($data[$savedCampaignId]->getStores() as $store) {
                $stores[] = $store->getStoreId();
            }
            $data[$savedCampaignId]['storeviews'] = implode($stores);

            $this->dataPersistor->clear('campaignData');
        }

        return $data;
    }

    /**
     * @param $filePath
     * @return array|null
     */
    private function getLogoData($filePath)
    {
        return $this->fileInfoCollector->getInfoByFilePath($filePath);
    }
}
