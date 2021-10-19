<?php

namespace Camoo\Enkap\Controller\Page;

require_once __DIR__ . '/../../vendor/autoload.php';

use \Magento\Framework\App\Action\HttpGetActionInterface;
use \Magento\Framework\Controller\Result\RedirectFactory;
use \Enkap\OAuth\Services\OrderService;
use \Enkap\OAuth\Model\Order;
use \Camoo\Enkap\Helper\Credentials;

class Index implements HttpGetActionInterface 
{
    protected $redirectFactory;
    protected $credentials;
  
    public function __construct(RedirectFactory $redirectFactory, Credentials $credentials) {
        $this->redirectFactory = $redirectFactory;
        $this->credentials = $credentials;
    }

    public function execute()
    {
        $key = $this->credentials->getGeneralConfig('public');
        $secret = $this->credentials->getGeneralConfig('private');

        $sandbox = !$this->credentials->getGeneralConfig('sandbox') ? false : true;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $sessionObj = $objectManager->get('\Magento\Checkout\Model\Type\Onepage'); 
        $session = $sessionObj->getCheckout();
        $shopOrder = $session->getLastRealOrder();

        $items = $shopOrder->getAllItems();

        $products = []; $product = [];
        foreach($items as $item) {
            $product['itemId'] = $item->getProductId();
            $product['particulars'] = $item->getName();
            $product['unitCost'] = (int)$item->getPrice();
            $product['quantity'] = (int)$item->getQtyOrdered();
            $products[] = $product;         
        }
        
        $orderService = new OrderService($key, $secret, [], $sandbox);
        $order = $orderService->loadModel(Order::class);
        $merchantReference = uniqid('secure', true);
        $data = [
            'merchantReference' => $merchantReference,
            'email' => $shopOrder->getCustomerEmail(),
            'customerName' => $shopOrder->getCustomerName(),
            'totalAmount' => (int)$shopOrder->getGrandTotal(),
            'description' => 'Camoo Payment',
            'currency' => 'XAF', //$shopOrder->getBaseCurrencyCode(),
            'items' => $products
        ];

        try {
            $order->fromStringArray($data);
            $response = $orderService->place($order);

            // Save references into your Database 
            $shopOrder->setOrderTransactionId($response->getOrderTransactionId());
            $shopOrder->setMerchantReference($merchantReference);
            $shopOrder->setStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
            $shopOrder->save();

            $redirect = $this->redirectFactory->create();
            $redirect->setUrl($response->getRedirectUrl());
            
            return $redirect;
            
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }
}