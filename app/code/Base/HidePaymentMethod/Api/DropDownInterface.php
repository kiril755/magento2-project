<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

interface DropDownInterface
{
    public const DATA_ARGUMENT = 'is_render_to_js_template';

    /**
     * Get shipping method drop down
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getShippingMethodDropDown() : BlockInterface;

    /**
     * Get payment method drop down
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getPaymentMethodDropDown() : BlockInterface;
}
