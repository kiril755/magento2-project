<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Request extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_wholesale_request', 'id');
    }
}
