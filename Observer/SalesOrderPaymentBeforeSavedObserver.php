<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Camoo
 * @package     Camoo_Enkap
 */

namespace Camoo\Enkap\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SalesOrderPaymentBeforeSavedObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();

        if (empty($payment)) {
            return $this;
        }

        if ($payment->getMethod() !== 'custompayment') {
            return $this;
        }

        return $this;
    }
}
