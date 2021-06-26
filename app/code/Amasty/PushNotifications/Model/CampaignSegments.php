<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model;

use Amasty\PushNotifications\Api\Data\CampaignSegmentsInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class CampaignSegments
 */
class CampaignSegments extends AbstractModel implements CampaignSegmentsInterface
{
    public function _construct()
    {
        parent::_construct();
        $this->_init(\Amasty\PushNotifications\Model\ResourceModel\CampaignSegments::class);
        $this->setIdFieldName(CampaignSegmentsInterface::CAMPAIGN_SEGMENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCampaignSegmentId()
    {
        return (int)$this->_getData(CampaignSegmentsInterface::CAMPAIGN_SEGMENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCampaignSegmentId($id)
    {
        return $this->setData(CampaignSegmentsInterface::CAMPAIGN_SEGMENT_ID, (int)$id);
    }

    /**
     * @inheritdoc
     */
    public function getSegmentId()
    {
        return (int)$this->_getData(CampaignSegmentsInterface::SEGMENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setSegmentId($id)
    {
        return $this->setData(CampaignSegmentsInterface::SEGMENT_ID, (int)$id);
    }

    /**
     * @inheritdoc
     */
    public function getCampaignId()
    {
        return (int)$this->_getData(CampaignSegmentsInterface::CAMPAIGN_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCampaignId($id)
    {
        return $this->setData(CampaignSegmentsInterface::CAMPAIGN_ID, (int)$id);
    }
}
