<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Controller\Adminhtml\Campaign;

use Amasty\PushNotifications\Api\CampaignRepositoryInterface;
use Amasty\PushNotifications\Api\Data\CampaignInterface;
use Amasty\PushNotifications\Model\Campaign;
use Amasty\PushNotifications\Model\CampaignFactory;
use Amasty\PushNotifications\Model\CampaignStoreFactory;
use Amasty\PushNotifications\Model\FileUploader\FileProcessor;
use Amasty\PushNotifications\Model\OptionSource\Campaign\SegmentationSource;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Save extends \Amasty\PushNotifications\Controller\Adminhtml\Campaign
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repository;

    /**
     * @var CampaignFactory
     */
    private $campaignFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var FileProcessor
     */
    private $fileProcessor;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var CampaignStoreFactory
     */
    private $campaignStoreFactory;

    public function __construct(
        Context $context,
        CampaignRepositoryInterface $repository,
        CampaignFactory $campaignFactory,
        DataPersistorInterface $dataPersistor,
        FileProcessor $fileProcessor,
        TimezoneInterface $timezone,
        CampaignStoreFactory $campaignStoreFactory
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->campaignFactory = $campaignFactory;
        $this->dataPersistor = $dataPersistor;
        $this->fileProcessor = $fileProcessor;
        $this->timezone = $timezone;
        $this->campaignStoreFactory = $campaignStoreFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     *
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = [];
            try {
                /** @var \Amasty\PushNotifications\Model\Campaign $model */
                $model = $this->campaignFactory->create();
                $data = $this->prepareData($this->getRequest()->getPostValue());
                $logoData = [];

                if (isset($data[CampaignInterface::LOGO_PATH])) {
                    $logoData = $data[CampaignInterface::LOGO_PATH];
                    unset($data[CampaignInterface::LOGO_PATH]);
                }

                $model->addData($data);
                $this->setExtData($model, $data);
                $this->repository->save($model);
                $this->saveLogoImage($model, $logoData);

                if ($this->getRequest()->getParam('save_and_send')) {
                    if (!empty($model->getEmail())) {
                        $this->getRequest()->setParams(['id' => $model->getId()]);
                        $this->_forward('send');

                        return;
                    }
                    $this->messageManager->addWarningMessage(__('Email can not be sent. Email field is empty.'));
                }

                $this->messageManager->addSuccessMessage(__('You saved the item.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);

                    return;
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('campaignData', $data);

                if ($model->getId()) {
                    $this->_redirect(
                        '*/*/edit',
                        $model->getId() ? ['id' => $model->getId()] : []
                    );
                }

                return;
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * @param $campaignModel
     * @param $logoData
     *
     * @return $this
     *
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Exception
     */
    private function saveLogoImage($campaignModel, $logoData)
    {
        if ($logoData) {
            $logoData = array_shift($logoData);
            $campaignId = $campaignModel->getCampaignId();
            $logoPath = $this->fileProcessor->saveTmp($logoData, $campaignId);

            if ($logoPath) {
                $campaignModel->setLogoPath($logoPath);
                $this->repository->save($campaignModel);
            }
        }

        return $this;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function prepareData($data)
    {
        if (isset($data[CampaignInterface::SCHEDULED])) {
            $data[CampaignInterface::SCHEDULED] = new \DateTime($data[CampaignInterface::SCHEDULED]);
        }

        $data[CampaignInterface::IS_DEFAULT_LOGO] = (int)!isset($data[CampaignInterface::LOGO_PATH]);

        return $data;
    }

    /**
     * @param \Amasty\PushNotifications\Model\Campaign $model
     *
     * @param $data
     *
     * @throws \Exception
     */
    private function setExtData($model, $data)
    {
        if (isset($data['storeviews'])) {
            $ids = $model->getStoreIds();
            $newRows = array_diff($data['storeviews'], $ids);
            $delRows = array_diff($ids, $data['storeviews']);
            if (count($data['storeviews']) > 1 && in_array(0, $data['storeviews'])) {
                $delRows[] = 0;
            }
            if (count($delRows)) {
                foreach ($delRows as $id) {
                    $model->deleteStore($id);
                }
            }
            if (count($newRows)) {
                foreach ($newRows as $id) {
                    $object = $this->campaignStoreFactory->create();
                    $object->setStoreId($id);
                    $model->addStore($object);
                }
            }
        }

        if (isset($data['segmentation_source'])) {
            if ($data['segmentation_source'] == SegmentationSource::CUSTOMER_GROUPS) {
                $this->setCustomerGroups($data, $model);
            } else {
                $this->setSegments($data, $model);
            }
        }
    }

    /**
     * @param array $data
     * @param Campaign $model
     */
    private function setCustomerGroups($data, $model)
    {
        $customerGroups = [];

        if (isset($data['customer_groups']) && !empty($data['customer_groups'])) {
            foreach ($data['customer_groups'] as $groupId) {
                $campaignCustomerGroup = $this->repository->getEmptyCampaignCustomerGroupModel();
                $campaignCustomerGroup->setGroupId($groupId);
                $customerGroups[] = $campaignCustomerGroup;
            }
        }

        $model->setCustomerGroups($customerGroups);
    }

    /**
     * @param array $data
     * @param Campaign $model
     */
    private function setSegments($data, $model)
    {
        $segments = [];

        if (isset($data['customer_segments']) && !empty($data['customer_segments'])) {
            foreach ($data['customer_segments'] as $segmentId) {
                $campaignSegment = $this->repository->getEmptyCampaignSegmentModel();
                $campaignSegment->setSegmentId($segmentId);
                $segments[] = $campaignSegment;
            }
        }

        $model->setSegments($segments);
    }
}
