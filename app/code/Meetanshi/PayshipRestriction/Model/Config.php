<?php
namespace Meetanshi\PayshipRestriction\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ValueInterface;
use Magento\Framework\DB\Transaction;
use Magento\Framework\DataObject;
use Magento\Framework\App\Config\ValueFactory;

class Config extends DataObject
{
    protected $storeManager;
    protected $scopeConfig;
    protected $backendModel;
    protected $transaction;
    protected $configValueFactory;
    protected $storeId;
    protected $storeCode;

    public function __construct(StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig, ValueInterface $backendModel, Transaction $transaction, ValueFactory $configValueFactory, array $data = [])
    {
        parent::__construct($data);
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->backendModel = $backendModel;
        $this->transaction = $transaction;
        $this->configValueFactory = $configValueFactory;
        $this->storeId=(int)$this->storeManager->getStore()->getId();
        $this->storeCode=$this->storeManager->getStore()->getCode();
    }
    
    public function getCurrentStoreConfigValue($path)
    {
        return $this->scopeConfig->getValue($path, 'store', $this->storeCode);
    }
    
    public function setCurrentStoreConfigValue($path, $value)
    {
        $data = [
                    'path' => $path,
                    'scope' =>  'stores',
                    'scope_id' => $this->storeId,
                    'scope_code' => $this->storeCode,
                    'value' => $value,
                ];

        $this->backendModel->addData($data);
        $this->transaction->addObject($this->backendModel);
        $this->transaction->save();
    }
}
