<?php

namespace Base\HidePaymentMethod\Model;

use Base\HidePaymentMethod\Api\PaymentMethodColumnInterface;
use Base\HidePaymentMethod\Model\DropDown;
use Magento\Framework\Exception\LocalizedException;

class PaymentMethodColumn implements PaymentMethodColumnInterface
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
     * Get payment method column
     *
     * @return array
     * @throws LocalizedException
     */
    public function getPaymentMethodColumn() : array
    {
        $label = self::LABEL;
        return
            [
                'label' => __($label),
                'class' => self::CLASS_NAME,
                'renderer' => $this->dropDown->getPaymentMethodDropDown()
            ];
    }
}
