<?php

namespace Camoo\Enkap\Controller\Adminhtml\Status;

require_once dirname(__DIR__, 3) .'/vendor/autoload.php';

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Enkap\OAuth\Services\PaymentService;
use Camoo\Enkap\Helper\Credentials;

class Index implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory;
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

        $sandbox = !$this->credentials->getGeneralConfig('sandbox') ? false : true;

        $order_id = $this->request->getParam('order_id');

        if($order_id){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $order = $objectManager->create('Magento\Sales\Model\Order')->load($order_id);
            $order_transaction_id = $order->getOrderTransactionId();
            if($order_transaction_id){
                $paymentService = new PaymentService($key, $secret, [], $sandbox);
                $payment = $paymentService->getByTransactionId($order_transaction_id);

                // Update your database
                $order->setStatus(strtolower($payment->getPaymentStatus()));
                $order->save();
            }
        }
        $redirect = $this->redirectFactory->create();
        $redirect->setRefererOrBaseUrl();

        return $redirect;
    }
}
