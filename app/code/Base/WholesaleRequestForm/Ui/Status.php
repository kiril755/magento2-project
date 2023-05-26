<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Ui;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    private const SUCCESS = 'success';
    private const PENDING = 'pending';
    private const REJECT = 'reject';
    /**
     * @return array[]
     */
    public function toOptionArray() : array
    {
        return [
            ['value' => self::SUCCESS, 'label' => __(self::SUCCESS)],
            ['value' => self::PENDING, 'label' => __(self::PENDING)],
            ['value' => self::REJECT, 'label' => __(self::REJECT)]
        ];
    }
}
