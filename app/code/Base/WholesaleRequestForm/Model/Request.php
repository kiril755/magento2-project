<?php
declare(strict_types=1);

namespace Base\WholesaleRequestForm\Model;

use Magento\Framework\Model\AbstractModel;

class Request extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Base\WholesaleRequestForm\Model\ResourceModel\Request::class);
    }
}
