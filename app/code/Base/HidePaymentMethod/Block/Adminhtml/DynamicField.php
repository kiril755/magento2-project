<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Base\HidePaymentMethod\Model\DropDown;
use Base\HidePaymentMethod\Model\ShippingMethodColumn;
use Base\HidePaymentMethod\Model\PaymentMethodColumn;
use Magento\Framework\Exception\LocalizedException;

class DynamicField extends AbstractFieldArray
{
    /**
     * @var DropDown
     */
    private $dropDown;
    /**
     * @var ShippingMethodColumn
     */
    private $shippingMethodColumn;
    /**
     * @var PaymentMethodColumn
     */
    private $paymentMethodColumn;

    /**
     * @param DropDown $dropDown
     * @param ShippingMethodColumn $shippingMethodColumn
     * @param PaymentMethodColumn $paymentMethodColumn
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        DropDown $dropDown,
        ShippingMethodColumn $shippingMethodColumn,
        PaymentMethodColumn $paymentMethodColumn,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dropDown = $dropDown;
        $this->shippingMethodColumn = $shippingMethodColumn;
        $this->paymentMethodColumn = $paymentMethodColumn;
    }

    /**
     * Prepare to render
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender() : void
    {
        $this->addColumn(
            'shipping_method',
            $this->shippingMethodColumn->getShippingMethodColumn()
        );
        $this->addColumn(
            'payment_method',
            $this->paymentMethodColumn->getPaymentMethodColumn()
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare array row
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row) : void
    {
        $options = [];

        $shippingMethod = $row->getData('shipping_method');
        if ($shippingMethod !== null) {
            $optionShippingMethodHash = $this->dropDown->getShippingMethodDropDown()->calcOptionHash($shippingMethod);
            $options['option_' . $optionShippingMethodHash] = 'selected="selected"';
        }

        $paymentMethod = $row->getData('payment_method');
        if ($paymentMethod !== null) {
            $optionPaymentMethodHash = $this->dropDown->getPaymentMethodDropDown()->calcOptionHash($paymentMethod);
            $options['option_' . $optionPaymentMethodHash] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
