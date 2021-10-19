<?php

namespace Camoo\Enkap\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

class Credentials extends AbstractHelper
{

	const XML_PATH_PAYMENT = 'payment/';

	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

	public function getGeneralConfig($code, $storeId = null)
	{
		return $this->getConfigValue(self::XML_PATH_PAYMENT .'custompayment/'. $code, $storeId);
	}

}








