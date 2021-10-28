<?php

namespace Camoo\Enkap\Controller\Notify;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Camoo\Enkap\Model\Smobilpay;
use Magento\Framework\App\Action\HttpPutActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order;

class Index implements HttpPutActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     */
    public function __construct(JsonFactory $jsonFactory, RequestInterface $request)
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
    }

    public function execute()
    {
        $objectManager = ObjectManager::getInstance();

        $referenceId = array_key_first($this->request->getParams());

        /** @var Smobilpay $paymentTransaction */
        $paymentTransaction = $objectManager->create(Smobilpay::class)->load($referenceId, 'merchant_reference');
        $oderIdentity = $paymentTransaction->getOrderId();

        /** @var Order $order */
        $order = $objectManager->create(Order::class)->load($oderIdentity);

        $output = json_decode(html_entity_decode(file_get_contents('php://input')), true);
        $payment_status = $output['status'];

        // Update your database
        $order->setStatus(strtolower($payment_status));
        $paymentTransaction->setStatus($payment_status);
        $paymentTransaction->setClientIp();
        $paymentTransaction->save();
        $order->save();

        $json = $this->jsonFactory->create();

        return $json->setData(['result' => 'OK']);
    }
}
