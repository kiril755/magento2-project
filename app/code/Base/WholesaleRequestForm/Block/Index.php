<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Index extends Template
{

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getNameData() : string
    {
        return $this->getName();
    }
}
