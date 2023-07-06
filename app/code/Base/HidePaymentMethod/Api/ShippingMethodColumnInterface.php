<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Api;

use Magento\Framework\Exception\LocalizedException;

interface ShippingMethodColumnInterface
{
    public const LABEL = 'Shipping method';
    public const CLASS_NAME = 'required-entry';

    /**
     * Get shipping method column
     *
     * @return array
     * @throws LocalizedException
     */
    public function getShippingMethodColumn() : array;
}
