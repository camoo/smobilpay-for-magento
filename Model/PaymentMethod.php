<?php

namespace Camoo\Enkap\Model;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PaymentMethod extends AbstractDb
{
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'custompayment';

    /**
         * Resource initialization
         *
         * @return void
         */
    protected function _construct()
    {
        $this->_init('smobilpay_payment_transaction', 'id');
    }

    /**
     * Add order relation to billing agreement
     *
    * @param int $agreementId
    * @param int $orderId
     * @return $this
     */
    public function addOrderHistory($agreementId, $orderId)
    {
           $this->getConnection()->insert(
            $this->getTable('smobilpay_payment_transaction'),
            ['agreement_id' => $agreementId, 'order_id' => $orderId]
            );
        return $this;
   }

}

