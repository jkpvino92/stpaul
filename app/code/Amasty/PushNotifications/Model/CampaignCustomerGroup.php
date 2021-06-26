<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model;

use Amasty\PushNotifications\Api\Data\CampaignCustomerGroupInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class CampaignCustomerGroup
 */
class CampaignCustomerGroup extends AbstractModel implements CampaignCustomerGroupInterface
{
    public function _construct()
    {
        parent::_construct();
        $this->_init(\Amasty\PushNotifications\Model\ResourceModel\CampaignCustomerGroup::class);
        $this->setIdFieldName(CampaignCustomerGroupInterface::CAMPAIGN_GROUP_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCampaignGroupId()
    {
        return (int)$this->_getData(CampaignCustomerGroupInterface::CAMPAIGN_GROUP_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCampaignGroupId($id)
    {
        return $this->setData(CampaignCustomerGroupInterface::CAMPAIGN_GROUP_ID, (int)$id);
    }

    /**
     * @inheritdoc
     */
    public function getGroupId()
    {
        return (int)$this->_getData(CampaignCustomerGroupInterface::GROUP_ID);
    }

    /**
     * @inheritdoc
     */
    public function setGroupId($id)
    {
        return $this->setData(CampaignCustomerGroupInterface::GROUP_ID, (int)$id);
    }

    /**
     * @inheritdoc
     */
    public function getCampaignId()
    {
        return (int)$this->_getData(CampaignCustomerGroupInterface::CAMPAIGN_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCampaignId($id)
    {
        return $this->setData(CampaignCustomerGroupInterface::CAMPAIGN_ID, (int)$id);
    }
}
