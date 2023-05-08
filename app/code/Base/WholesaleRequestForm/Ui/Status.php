<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Ui;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray() : array
    {
        return [
            ['value' => 'success', 'label' => __('success')],
            ['value' => 'pending', 'label' => __('pending')],
            ['value' => 'reject', 'label' => __('reject')]
        ];
    }
}
