<?php


namespace Base\HidePaymentMethod\Model;

use Base\HidePaymentMethod\Api\DropDownInterface;
use Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom\PaymentMethod;
use Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom\ShippingMethod;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;

class DropDown implements DropDownInterface
{
    /**
     * @var BlockInterface
     */
    private $shippingMethodDropDownMethod;

    /**
     * @var BlockInterface
     */
    private $paymentMethodDropDownMethod;
    /**
     * @var Template
     */
    private $template;

    /**
     * @param Template $template
     */
    public function __construct(
        Template $template
    ) {
        $this->template = $template;
    }

    /**
     * Get shipping method drop down
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getShippingMethodDropDown() : BlockInterface
    {
        if (!$this->shippingMethodDropDownMethod) {
            $this->shippingMethodDropDownMethod = $this->template->getLayout()->createBlock(
                ShippingMethod::class,
                '',
                [
                    'data' => [
                        self::DATA_ARGUMENT => true
                    ]
                ]
            );
        }
        return $this->shippingMethodDropDownMethod;
    }

    /**
     * Get payment method drop down
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getPaymentMethodDropDown() : BlockInterface
    {
        if (!$this->paymentMethodDropDownMethod) {
            $this->paymentMethodDropDownMethod = $this->template->getLayout()->createBlock(
                PaymentMethod::class,
                '',
                [
                    'data' => [
                        self::DATA_ARGUMENT => true
                    ]
                ]
            );
        }
        return $this->paymentMethodDropDownMethod;
    }
}
