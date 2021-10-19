<?php

namespace Camoo\Enkap\Controller\Success;

require_once __DIR__ . '/../../vendor/autoload.php';

use \Magento\Framework\App\Action\HttpGetActionInterface;
use \Magento\Framework\Controller\Result\RedirectFactory;
use \Magento\Framework\App\RequestInterface;
use \Enkap\OAuth\Services\StatusService;
use \Camoo\Enkap\Helper\Credentials;

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
 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->load(array_key_first($this->request->getParams()), 'merchant_reference');
        $order_transaction_id = $order->getOrderTransactionId();
        $payment_status = $this->request->getParam('status');

        $statusService = new StatusService($key, $secret, [], $sandbox);
        $status = $statusService->getByTransactionId($order_transaction_id);

        // Update your database
        $order->setStatus(strtolower($payment_status));
        $order->save();

        $redirect = $this->redirectFactory->create();

        if ($status->confirmed()){
            // Payment successfully completed
            // send Item to user/customer
        }

        if ($status->failed() || $status->canceled()) {
            // delete that reference from your Database
            $redirect->setPath('checkout/cart');
            return $redirect;
        }

        $redirect->setPath('checkout/onepage/success');
        return $redirect;
    }
}