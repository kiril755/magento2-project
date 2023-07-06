<?php
declare(strict_types=1);

namespace Base\HidePaymentMethod\Api;

use Magento\Framework\Exception\LocalizedException;

interface PaymentMethodColumnInterface
{
    public const LABEL = 'Payment method';
    public const CLASS_NAME = 'required-entry';

    /**
     * Get payment method column
     *
     * @return array
     * @throws LocalizedException
     */
    public function getPaymentMethodColumn() : array;
}
