<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Camoo\Enkap\Block\Adminhtml\Order\View;

use Camoo\Enkap\Model\Smobilpay;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\Order;

class Info extends \Magento\Sales\Block\Adminhtml\Order\View\Info
{
    public function getCustomOrderUrl(Order $order)
    {
       return $this->getUrl('enkap/status/index', ['order_id' => $order->getId()]);
    }

    /**
     * @param  Order $order
     * @return string
     */
    public function getOrderTransactionId(Order $order): ?string
    {
        $objectManager = ObjectManager::getInstance();
        /** @var Smobilpay $paymentTransaction */
        $paymentTransaction = $objectManager->create(Smobilpay::class)->load($order->getEntityId(), 'order_id');
       return $paymentTransaction->getOrderTransactionId();
    }

    public function getMerchantReference(Order $order): ?string
    {
        $objectManager = ObjectManager::getInstance();
        /** @var Smobilpay $paymentTransaction */
        $paymentTransaction = $objectManager->create(Smobilpay::class)->load($order->getEntityId(), 'order_id');
       return $paymentTransaction->getMerchantReferenceId();
    }
}
