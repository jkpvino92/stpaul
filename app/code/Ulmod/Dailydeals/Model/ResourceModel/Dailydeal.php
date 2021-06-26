<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Model\ResourceModel;

class Dailydeal extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Date time handler
     *
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * Date model
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,	
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {    
        $this->dateTime = $dateTime;
        $this->date     = $date;
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('um_dailydeals_dailydeal', 'dailydeal_id');
    }

    /**
     * Retrieves Dailydeal Product Sku from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getDailydealUm_product_skuById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'um_product_sku')
            ->where('dailydeal_id = :dailydeal_id');
        $binds = ['dailydeal_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
	
    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Ulmod\Dailydeals\Model\Dailydeal $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setUpdatedAt($this->date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->date());
        }
        foreach (['um_date_from', 'um_date_to'] as $field) {
            $value = !$object->getData($field) ? null : $object->getData($field);
            $object->setData($field, $this->dateTime->formatDate($value));
        }
        return parent::_beforeSave($object);
    }
}
