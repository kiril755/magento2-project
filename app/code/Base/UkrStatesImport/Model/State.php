<?php
declare(strict_types=1);

namespace Base\UkrStatesImport\Model;

use Magento\Framework\Model\AbstractModel;

class State extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Base\UkrStatesImport\Model\ResourceModel\State::class);
    }
}
