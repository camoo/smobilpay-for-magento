<?php

namespace Camoo\Enkap\Api\Data;

interface SmobilpayInterface
{
    public const ENTITY_ID = 'id';
    public const ORDER_ID = 'order_id';
    public const MERCHANT_REFERENCE = 'merchant_reference';
    public const ORDER_TRANSACTION_ID = 'order_transaction_id';
    public const STATUS = 'status';
    public const CLIENT_IP = 'remote_ip';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
}
