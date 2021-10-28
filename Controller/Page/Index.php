<?php

namespace Camoo\Enkap\Controller\Page;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';


use Camoo\Cache\Cache;
use Camoo\Cache\CacheConfig;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Enkap\OAuth\Services\OrderService;
use Enkap\OAuth\Model\Order;
use Camoo\Enkap\Helper\Credentials;
use Magento\Tests\NamingConvention\true\string;
use stdClass;
use Throwable;

class Index implements HttpGetActionInterface
{
    protected $redirectFactory;
    protected $credentials;

    public function __construct(RedirectFactory $redirectFactory, Credentials $credentials)
    {
        $this->redirectFactory = $redirectFactory;
        $this->credentials = $credentials;
    }

    public function execute()
    {
        $key = $this->credentials->getGeneralConfig('public');
        $secret = $this->credentials->getGeneralConfig('private');

        $sandbox = (bool)$this->credentials->getGeneralConfig('sandbox');

        $objectManager = ObjectManager::getInstance();

        /** @var Onepage $sessionObj */
        $sessionObj = $objectManager->get(Onepage::class);
        $session = $sessionObj->getCheckout();
        $shopOrder = $session->getLastRealOrder();

        $items = $shopOrder->getAllItems();

        $currency = $shopOrder->getBaseCurrencyCode();
        $currencyRate = $this->getCurrencyRate($currency);
        $products = [];
        $product = [];
        foreach ($items as $item) {
            $product['itemId'] = $item->getProductId();
            $product['particulars'] = $item->getName();
            $product['unitCost'] = (float)ceil($item->getPrice() * $currencyRate);
            $product['subTotal'] = (float)ceil($item->getPrice() * $currencyRate);
            $product['quantity'] = (int)$item->getQtyOrdered();
            $products[] = $product;
        }

        $orderService = new OrderService($key, $secret, [], $sandbox);
        $order = $orderService->loadModel(Order::class);
        $merchantReference = uniqid('mo-', true);
        $data = [
            'merchantReference' => $merchantReference,
            'email' => $shopOrder->getCustomerEmail(),
            'customerName' => $shopOrder->getCustomerName(),
            'totalAmount' => (float)ceil($shopOrder->getGrandTotal() * $currencyRate),
            'description' => 'Magento Site Order',
            'currency' => 'XAF',
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

        } catch (Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    protected function getCurrencyRate(string $siteCurrency)
    {
        $cache = new Cache(CacheConfig::fromArray(['encrypt' => false]));

        if ($siteCurrency === 'XAF') {
            return 1;
        }
        if (empty($siteCurrency)) {
            $siteCurrency = 'EUR';
        }

        $currencyCacheKey = 'currency_' . $siteCurrency;
        $url = 'https://open.er-api.com/v6/latest/' . $siteCurrency;
        $currencies = $cache->read($currencyCacheKey);
        if (empty($currencies)) {
            $currencies = $this->sendCurl($url);
            $currencyData = json_decode($currencies, true);
            $expireIn = (int)$currencyData['time_next_update_unix'] - time();
            $cache->write($currencyCacheKey, $currencies, $expireIn);
        } else {
            $currencyData = json_decode($currencies, true);
        }


        $rates = $currencyData['rates'];
        if (!array_key_exists('XAF', $rates)) {
            return null;
        }
        return $rates['XAF'];
    }


    protected function sendCurl($url)
    {
        $ch = curl_init($url);

        $header = [
            "Content-Type: application/json",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, "SmobilPay-Magento/CamooClient/");

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

        $response = curl_exec($ch);
        if (curl_errno($ch) != CURLE_OK) {
            $response = new stdClass();
            $response->Errors = "POST Error: " . curl_error($ch) . " URL: $url";
            $response = json_encode($response);
        } else {
            $info = curl_getinfo($ch);
            $httpCode = $info['http_code'];
            if (!in_array($httpCode, [200, 201])) {
                $response = new stdClass();
                if ($info['http_code'] == 401 || $info['http_code'] == 404 || $info['http_code'] == 403) {
                    $response->Errors = "Please check the API Key and Password";
                } else {
                    $response->Errors = 'Error connecting : ' . $info['http_code'];
                }
                $response = json_encode($response);
            }
        }

        curl_close($ch);

        return $response;
    }
}
