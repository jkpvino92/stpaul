<?php
namespace Meetanshi\PayshipRestriction\Plugin;

use Magento\Payment\Model\PaymentMethodList;
use Meetanshi\PayshipRestriction\Helper\Data;

class RestrictPaymentMethods
{
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function afterGetActiveList(PaymentMethodList $subject, $result)
    {
        $methods = $result;
        foreach ($methods as $k => $method) {
            if (!$this->helper->canUseMethod($method->getCode(), 'payment')) {
                unset($methods[$k]);
            }
        }
        return $methods;
    }
}
