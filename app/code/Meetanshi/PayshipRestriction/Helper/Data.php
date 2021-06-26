<?php
namespace Meetanshi\PayshipRestriction\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Meetanshi\PayshipRestriction\Model\ResourceModel\PayshipRestriction\CollectionFactory;
use Magento\Payment\Model\Config;
use Magento\Shipping\Model\Config as shippingConfig;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as customerGroupCollection;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Method\Factory;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    static protected $customerGroupId = null;
    protected $storeManager = null;
    protected $collectionFactory;
    protected $paymentConfig;
    protected $shippingConfig;
    protected $customerGroupCollection;
    protected $customerSession;
    protected $request;
    public $scopeConfig;
    protected $paymentFactory;

    public function __construct(Context $context, StoreManagerInterface $storeManager, CollectionFactory $collectionFactory, Config $paymentConfig, shippingConfig $shippingConfig, customerGroupCollection $customerGroupCollection, Session $customerSession,Http $request,ScopeConfigInterface $scopeConfig,Factory $factory)
    {
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
        $this->paymentConfig = $paymentConfig;
        $this->shippingConfig = $shippingConfig;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
        $this->paymentFactory = $factory;
        parent::__construct($context);
    }

    public function getMethodsVisibility($type, $websiteId, $method = null)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('type', ['eq' => $type]);
        if ($method !== null) {
            $collection->addFieldToFilter('method', ['eq' => $method]);
        }
        $collection->addFieldToFilter('website_id', ['eq' => $websiteId]);
        return $collection->load();
    }

    public function getActivePaymentMethods($website_id = null)
    {
        if ($website_id == null) {
            $website_id = $this->request->getParam('website_id');
        }
        $methods = [];

        foreach ($this->scopeConfig->getValue('payment', ScopeInterface::SCOPE_STORE, $website_id) as $code => $data) {
            if (isset($data['active'], $data['model']) && (bool)$data['active']) {
                $methodModel = $this->paymentFactory->create($data['model']);
                $methodModel->setStore(null);
                if ($methodModel->getConfigData('active', null)) {
                    $methods[$code] = $methodModel;
                }
            }
        }
        //return $this->paymentConfig->getActiveMethods();
        return $methods;
    }

    public function getActiveShippingMethods($website_id = null)
    {
        if ($website_id == null) {
            $website_id = $this->request->getParam('website_id');
        }
        return $this->shippingConfig->getActiveCarriers($website_id);
    }

    public function getCustomerGroup()
    {
        return $this->customerGroupCollection->create();
    }

    public function canUseMethod($method, $type)
    {
        if ($this->isEnabled() == 0) {
            return true;
        }
        if ($type == 'payment') {
            return $this->_canUsePaymentMethod($method);
        }
        if ($type == 'shipping') {
            return $this->_canUseShippingMethod($method);
        }
        return true;
    }

    public function _canUseShippingMethod($method)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $type = 'shipping';
        $flag = false;
        $collection = $this->getMethodsVisibility($type, $websiteId, $method);

        foreach ($collection as $methods) {
            if ($methods->getRestrictionId()) {
                if ($methods->getCustomerGroupIds() != '') {
                    $allowedGroups = explode(',', $methods->getCustomerGroupIds());
                    if (in_array($this->_getCustomerGroupId(), $allowedGroups)) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                } else {
                    $flag = false;
                }
            } else {
                $flag = true;
            }
        }

        if ($flag) {
            return true;
        }

        return false;
    }

    protected function _canUsePaymentMethod($method)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $type = 'payment';
        $flag = false;
        $collection = $this->getMethodsVisibility($type, $websiteId, $method);
        
        foreach ($collection as $methods) {
            if ($methods->getRestrictionId()) {
                if ($methods->getCustomerGroupIds() != '') {
                    $allowedGroups = explode(',', $methods->getCustomerGroupIds());
                    if (in_array($this->_getCustomerGroupId(), $allowedGroups)) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                } else {
                    $flag = false;
                }
            } else {
                $flag = true;
            }
        }

        if ($flag) {
            return true;
        }

        return false;
    }

    protected function _getCustomerGroupId()
    {
        $customerSession = $this->customerSession;

        if (!(self::$customerGroupId ===null)) {
            return self::$customerGroupId;
        }
        if (!$customerSession->getId()) {
            return 0;
        }
        $roleId = $customerSession->getCustomerGroupId();
        return $roleId;
    }

    public function isEnabled()
    {
        $store = $this->storeManager->getStore();
        return $this->scopeConfig->getValue('psrestrict/general/enabled', ScopeInterface::SCOPE_STORE, $store);
    }
}
