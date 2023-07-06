<?php

namespace Base\HidePaymentMethod\Model;

use Base\HidePaymentMethod\Api\ShippingMethodColumnInterface;
use Base\HidePaymentMethod\Model\DropDown;
use Magento\Framework\Exception\LocalizedException;

class ShippingMethodColumn implements ShippingMethodColumnInterface
{
    /**
     * @var DropDown
     */
    private $dropDown;

    /**
     * @param DropDown $dropDown
     */
    public function __construct(
        DropDown $dropDown
    ) {
        $this->dropDown = $dropDown;
    }

    /**
     * Get shipping method column
     *
     * @return array
     * @throws LocalizedException
     */
    public function getShippingMethodColumn() : array
    {
        $label = self::LABEL;
        return
            [
                'label' => __($label),
                'class' => self::CLASS_NAME,
                'renderer' => $this->dropDown->getShippingMethodDropDown()
            ];
    }
}
