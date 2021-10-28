<?php

namespace Camoo\Enkap\Controller\Success;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Camoo\Enkap\Model\Smobilpay;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Enkap\OAuth\Services\StatusService;
use Camoo\Enkap\Helper\Credentials;
use Magento\Sales\Model\Order;

class Index implements HttpGetActionInterface
{
    /**
     * @var RedirectFactory;
     */
    protected $redirectFactory;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param Credentials $credentials
     */
    public function __construct(RedirectFactory $redirectFactory, RequestInterface $request, Credentials $credentials) {
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->credentials = $credentials;
    }

    public function execute()
    {
        $key = $this->credentials->getGeneralConfig('public');
        $secret = $this->credentials->getGeneralConfig('private');

        $sandbox = (bool)$this->credentials->getGeneralConfig('sandbox');

        $objectManager = ObjectManager::getInstance();

        $referenceId = array_key_first($this->request->getParams());


        /** @var Smobilpay $paymentTransaction */
        $paymentTransaction = $objectManager->create(Smobilpay::class)->load($referenceId, 'merchant_reference');
        $oderIdentity = $paymentTransaction->getOrderId();

        /** @var Order $order */
        $order = $objectManager->create(Order::class)->load($oderIdentity);

        $order_transaction_id = $paymentTransaction->getOrderTransactionId();
        $payment_status = $this->request->getParam('status');

        $statusService = new StatusService($key, $secret, [], $sandbox);
        $status = $statusService->getByTransactionId($order_transaction_id);

        // Update your database
        $order->setStatus(strtolower($payment_status));
        $paymentTransaction->setStatus($payment_status);
        $paymentTransaction->setClientIp();
        $paymentTransaction->save();
        $order->save();
        $redirect = $this->redirectFactory->create();

        if ($status->failed() || $status->canceled()) {
            // delete that reference from your Database
            $redirect->setPath('checkout/cart');
            return $redirect;
        }

        $redirect->setPath('checkout/onepage/success');
        return $redirect;
    }
}
