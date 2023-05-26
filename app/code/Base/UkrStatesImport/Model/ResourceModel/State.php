<?php
declare(strict_types=1);

namespace Base\UkrStatesImport\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class State extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('ua_states', 'id');
    }
}
