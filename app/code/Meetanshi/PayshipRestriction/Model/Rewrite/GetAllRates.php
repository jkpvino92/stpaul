<?php

namespace Meetanshi\PayshipRestriction\Model\Rewrite;

use Meetanshi\PayshipRestriction\Helper\Data;

class GetAllRates
{
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function afterGetAllRates($subject, $result)
    {
        foreach ($result as $key => $rate) {
            if (!$this->helper->canUseMethod($rate->getCarrier(), 'shipping')) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
