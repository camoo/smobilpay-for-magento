<?php

namespace Camoo\Enkap\Controller\Adminhtml\Status;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use Camoo\Enkap\Model\Smobilpay;
use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Enkap\OAuth\Services\PaymentService;
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
    public function __construct(RedirectFactory $redirectFactory, RequestInterface $request, Credentials $credentials)
    {
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->credentials = $credentials;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $key = $this->credentials->getGeneralConfig('public');
        $secret = $this->credentials->getGeneralConfig('private');

        $sandbox = (bool)$this->credentials->getGeneralConfig('sandbox');

        $order_id = $this->request->getParam('order_id');
        $redirect = $this->redirectFactory->create();
        $redirect->setRefererOrBaseUrl();
        if (empty($order_id)) {
            return $redirect;
        }
        $objectManager = ObjectManager::getInstance();
        /** @var Order $order */
        $order = $objectManager->create(Order::class)->load($order_id);

        /** @var Smobilpay $paymentTransaction */
        $paymentTransaction = $objectManager->create(Smobilpay::class)->load($order->getEntityId(), 'order_id');

        $order_transaction_id = $paymentTransaction->getOrderTransactionId();
        if (empty($order_transaction_id)) {
            return $redirect;
        }
        $paymentService = new PaymentService($key, $secret, [], $sandbox);
        $payment = $paymentService->getByTransactionId($order_transaction_id);

        $currentStatus = $payment->getPaymentStatus();
        $paymentTransaction->setStatus($currentStatus);
        $paymentTransaction->setClientIp();
        $paymentTransaction->save();
        // Update your database
        $order->setStatus(strtolower($currentStatus));
        $order->save();

        return $redirect;
    }
}
