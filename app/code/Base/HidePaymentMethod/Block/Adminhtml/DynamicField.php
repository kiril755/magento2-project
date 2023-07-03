<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom\ShippingMethod;
use Base\HidePaymentMethod\Block\Adminhtml\FormCustom\FieldCustom\PaymentMethod;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
class DynamicField extends AbstractFieldArray
{
    private $shippingMethodDropDownMethod;
    private $paymentMethodDropDownMethod;

    /**
     * @return void
     */
    protected function _prepareToRender() : void
    {
        $this->addColumn(
            'shipping_method',
            [
                'label' => __('Delivery method'),
                'class' => 'required-entry',
                'renderer' => $this->getShippingMethodDropDown(),
            ]
        );
        $this->addColumn(
            'payment_method',
            [
                'label' => __('Shipping method'),
                'class' => 'required-entry',
                'renderer' => $this->getPaymentMethodDropDown()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row) : void
    {
        $options = [];

        $shippingMethod = $row->getData('shipping_method');
        if ($shippingMethod !== null) {
            $options['option_' . $this->getShippingMethodDropDown()->calcOptionHash($shippingMethod)] = 'selected="selected"';
        }

        $paymentMethod = $row->getData('payment_method');
        if ($paymentMethod !== null) {
            $options['option_' . $this->getPaymentMethodDropDown()->calcOptionHash($paymentMethod)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getShippingMethodDropDown() : BlockInterface
    {
        if (!$this->shippingMethodDropDownMethod) {
            $this->shippingMethodDropDownMethod = $this->getLayout()->createBlock(
                ShippingMethod::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]);
        }
        return $this->shippingMethodDropDownMethod;
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getPaymentMethodDropDown() : BlockInterface
    {
        if (!$this->paymentMethodDropDownMethod) {
            $this->paymentMethodDropDownMethod = $this->getLayout()->createBlock(
                PaymentMethod::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]);
        }
        return $this->paymentMethodDropDownMethod;
    }
}
