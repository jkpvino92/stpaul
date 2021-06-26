<?php
namespace Meetanshi\PayshipRestriction\Block\Adminhtml\PayshipRestriction;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\Action\Context as appContext;
use Magento\Framework\Registry;
use Meetanshi\PayshipRestriction\Helper\Data;
use Magento\Store\Model\ScopeInterface;

class PayshipRestriction extends Template
{
    protected $type = '';
    protected $visibility = [];
    protected $request;
    protected $dataHelper;
    protected $coreRegistry;
    protected $modelMethodsFactory;
 
    public function __construct(Context $context, appContext $appContext, Registry $registry, Data $dataHelper, array $data = [])
    {
        $this->type = $appContext->getRequest()->getActionName();
        $this->request = $appContext->getRequest();
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
        $this->_prepareVisibility();
    }

    protected function _prepareVisibility()
    {
        $collection = $this->dataHelper->getMethodsVisibility($this->type, $this->getCurrentWebsite());
        foreach ($collection as $method) {
            $this->visibility[$method->getMethod()] = explode(',', $method->getCustomerGroupIds());
        }
    }

    public function getMethodsType()
    {
        return ucwords($this->type);
    }

    public function getMethods()
    {
        if ('payment' == $this->type) {
            $methods = $this->_getPaymentMethods();
        } elseif ('shipping' == $this->type) {
            $methods = $this->_getShippingMethods();
        }
        return $methods;
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => 'true']);
    }

    public function getWebsiteUrl($website = null)
    {
        if ($website === null) {
            $websiteId = 1;
        } else {
            $websiteId = $website->getId();
        }
        return $this->getUrl('*/*/*', ['website_id' => $websiteId, '_current' => true]);
    }
 
    public function getWebsites()
    {
        $websites = $this->_storeManager->getWebsites();
        return $websites;
    }

    public function getCurrentWebsite()
    {
        $websiteId = $this->request->getParam('website_id', 1);
        return $websiteId;
    }

    public function getCustomerGroups()
    {
        $groups = $this->dataHelper->getCustomerGroup();
        
        foreach ($groups as $eachGroup) {
            $option['value'] = $eachGroup->getCustomerGroupId();
            $option['label'] = $eachGroup->getCustomerGroupCode();
            $options[] = $option;
        }
        return $options;
    }

    public function isGroupSelected($group, $methodCode)
    {
        if (isset($this->visibility[$methodCode]) && in_array($group['value'], $this->visibility[$methodCode])) {
            return true;
        }
        return false;
    }

    protected function _getPaymentMethods()
    {
        $payments = $this->dataHelper->getActivePaymentMethods();
        $methods = [];
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->_scopeConfig->getValue('payment/'.$paymentCode.'/title', ScopeInterface::SCOPE_STORE);
            $methods[$paymentCode] = [
                'title'   => $paymentTitle,
                'value' => $paymentCode
            ];
        }
        return $methods;
    }

    protected function _getShippingMethods()
    {
        $shipping = $this->dataHelper->getActiveShippingMethods();
        $methods = [];
 
        foreach ($shipping as $shippingCode => $shippingModel) {
            $paymentTitle = $this->_scopeConfig->getValue('carriers/'.$shippingCode.'/title', ScopeInterface::SCOPE_STORE);
            $methods[$shippingCode] = [
                'title'   => $paymentTitle,
                'value' => $shippingCode
             ];
        }
        return $methods;
    }
}
