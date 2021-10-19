<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Camoo\Enkap\Block\Adminhtml\Order\View;

class Info extends \Magento\Sales\Block\Adminhtml\Order\View\Info
{
    public function getCustomOrderUrl($order)
    {
       return $this->getUrl('enkap/status/index', ['order_id' => $order->getId()]);
    }

    public function getOrderTransactionId($order)
    {
       return $order->getOrderTransactionId();
    }

    public function getMerchantReference($order)
    {
       return $order->getMerchantReference();
    }
}