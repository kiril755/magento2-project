<?php

namespace Base\UkrPoshta\Model\ResourceModel\State;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Base\UkrPoshta\Model\State;
use Base\UkrPoshta\Model\ResourceModel\State as StateResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            State::class,
            StateResource::class
        );
    }
}

