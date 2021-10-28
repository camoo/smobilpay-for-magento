<?php

namespace Camoo\Enkap\Model;

use Camoo\Enkap\Api\Data\SmobilpayInterface;
use Datetime;
use Exception;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Smobilpay extends AbstractModel implements SmobilpayInterface
{
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param RemoteAddress $remoteAddress
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Registry $registry,
        RemoteAddress $remoteAddress,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->remoteAddress = $remoteAddress;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Smobilpay::class);
    }

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'smobilpay_payment_transaction';

    /**
     * @var string
     */
    protected $_cacheTag = 'smobilpay_payment_transaction';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'smobilpay';


    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId): Smobilpay
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Title.
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set Order ID.
     */
    public function setOrderId(int $oderId): Smobilpay
    {
        return $this->setData(self::ORDER_ID, $oderId);
    }

    /**
     * Get merchant reference ID.
     *
     * @return string
     */
    public function getMerchantReferenceId(): ?string
    {
        return $this->getData(self::MERCHANT_REFERENCE);
    }

    /**
     * Set merchant reference ID.
     */
    public function setMerchantReferenceId(string $merchantRefId): Smobilpay
    {
        return $this->setData(self::MERCHANT_REFERENCE, $merchantRefId);
    }

    /**
     * Get OrderTransactionID.
     *
     * @return string
     */
    public function getOrderTransactionId(): ?string
    {
        return $this->getData(self::ORDER_TRANSACTION_ID);
    }

    /**
     * Set OrderTransactionID.
     */
    public function setOrderTransactionId(string $trxId): Smobilpay
    {
        return $this->setData(self::ORDER_TRANSACTION_ID, $trxId);
    }

    /**
     * Get Status.
     *
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Status.
     */
    public function setStatus(string $status): Smobilpay
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Client IP.
     *
     * @return string
     */
    public function getClientIp(): string
    {
        return $this->getData(self::CLIENT_IP);
    }

    /**
     * Set Client IP.
     */
    public function setClientIp(?string $ip = null): Smobilpay
    {
        $ip = $ip ?? $this->remoteAddress->getRemoteAddress();
        return $this->setData(self::CLIENT_IP, $ip);
    }

    /**
     * Get CreatedAt.
     *
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedAt(): Datetime
    {
        $createdAt = $this->getData(self::CREATED_AT);

        try {
            return new Datetime($createdAt);
        } catch (Exception $exception) {
            $createdAt = 'now';
        }
        return new Datetime($createdAt);
    }

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt): Smobilpay
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get UpdatedAt.
     *
     * @return DateTime
     * @throws Exception
     */
    public function getUpdatedAt(): Datetime
    {
        $createdAt = $this->getData(self::UPDATED_AT);

        try {
            return new Datetime($createdAt);
        } catch (Exception $exception) {
            $createdAt = 'now';
        }
        return new Datetime($createdAt);
    }

    /**
     * Set UpdatedAt.
     */
    public function setUpdatedAt(string $updatedAt): Smobilpay
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}

