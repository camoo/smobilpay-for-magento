<?php

namespace Camoo\Enkap\Controller\Notify;

require_once __DIR__ . '/../../vendor/autoload.php';

use \Magento\Framework\App\Action\HttpPutActionInterface;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\App\RequestInterface;

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
    public function __construct(JsonFactory $jsonFactory, RequestInterface $request) {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
    }

    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
  
        $order = $objectManager->create('Magento\Sales\Model\Order')->load(array_key_first($this->request->getParams()), 'merchant_reference');
        $output = json_decode(html_entity_decode(file_get_contents('php://input')), true);
        // status
        $order->setStatus(strtolower($output['status']));
        $order->save();
    
        $json = $this->jsonFactory->create();
    
        return $json->setData(['result' => 'OK']);
    }
}