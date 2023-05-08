<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Model\ResourceModel\Request;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Base\WholesaleRequestForm\Model\Request;
use Base\WholesaleRequestForm\Model\ResourceModel\Request as RequestResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            Request::class,
            RequestResource::class
        );
    }
}

