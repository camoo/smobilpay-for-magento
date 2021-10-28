<?php

namespace Camoo\Enkap\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Smobilpay extends AbstractDb
{

    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param Context $context
     * @param DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime       $date,
       ?string $resourcePrefix = null
    )
    {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('smobilpay_payment_transaction', 'id');
    }

}
