<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ulmod\Dailydeals\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Deal extends Template
{	
	const DEALS_SIDEBAR = 1;

    /**
     * @var int|null
     */
    private $blockPosition;

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve block position
     *
     * @return int|null
     */
    private function getBlockPosition()
    {
        if (!$this->blockPosition) {
            if (false !== strpos($this->getNameInLayout(), 'deals_sidebar')) {
                $this->blockPosition = self::DEALS_SIDEBAR;
            }			
        }
        return $this->blockPosition;
    }
}
