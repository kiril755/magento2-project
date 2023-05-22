<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Model;

use Magento\Framework\Model\AbstractModel;

class State extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Base\UkrPoshta\Model\ResourceModel\State::class);
    }
}
