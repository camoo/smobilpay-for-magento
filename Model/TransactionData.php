<?php

namespace Camoo\Enkap\Model;

use Magento\Framework\App\ResourceConnection;

class TransactionData
{

    protected $resource;

    public function __construct(
        ResourceConnection $resource
    )
    {
        $this->resource = $resource;
    }

    public function getQueryDataBySaleOrderId(int $entityId): array
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('smobilpay_payment_transaction');
        return $connection->fetchAssoc("SELECT `merchant_reference_id`, `order_transaction_id`, `status` FROM " . $tableName . " WHERE `order_id` =:id", [
            'id' => $entityId
        ]);
    }
}
