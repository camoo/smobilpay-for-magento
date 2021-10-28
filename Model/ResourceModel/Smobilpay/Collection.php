<?php

namespace Camoo\Enkap\Model\ResourceModel\Smobilpay;

use Camoo\Enkap\Model\Smobilpay;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(Smobilpay::class, \Camoo\Enkap\Model\ResourceModel\Smobilpay::class);
    }
}
