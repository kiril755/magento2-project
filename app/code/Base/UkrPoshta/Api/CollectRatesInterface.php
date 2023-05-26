<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Api;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

interface CollectRatesInterface
{
    /**
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request) : Result|bool;
}
