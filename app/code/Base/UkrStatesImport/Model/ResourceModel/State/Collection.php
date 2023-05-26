<?php
declare(strict_types=1);

namespace Base\UkrStatesImport\Model\ResourceModel\State;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Base\UkrStatesImport\Model\State;
use Base\UkrStatesImport\Model\ResourceModel\State as StateResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            State::class,
            StateResource::class
        );
    }
}

