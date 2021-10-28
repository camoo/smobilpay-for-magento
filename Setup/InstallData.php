<?php

namespace Camoo\Enkap\Setup;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;


class InstallData implements InstallDataInterface
{
    const STATUS_CONFIRMED = 'confirmed';
    const STATE_CONFIRMED = 'confirmed';
    const LABEL_CONFIRMED = 'Confirmed';

    const STATUS_CREATED = 'created';
    const STATE_CREATED = 'created';
    const LABEL_CREATED = 'Created';

    const STATUS_FAILED = 'failed';
    const STATE_FAILED = 'failed';
    const LABEL_FAILED = 'Failed';

    const STATUS_INITIALISED = 'initialised';
    const STATE_INITIALISED = 'initialised';
    const LABEL_INITIALISED = 'Initialised';

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATE_IN_PROGRESS = 'in_progress';
    const LABEL_IN_PROGRESS = 'In progress';

    protected $statusFactory;
    protected $statusResourceFactory;

    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addCustomOrderStatus(self::STATUS_CONFIRMED, self::STATE_CONFIRMED, self::LABEL_CONFIRMED);
        $this->addCustomOrderStatus(self::STATUS_CREATED, self::STATE_CREATED, self::LABEL_CREATED);
        $this->addCustomOrderStatus(self::STATUS_FAILED, self::STATE_FAILED, self::LABEL_FAILED);
        $this->addCustomOrderStatus(self::STATUS_INITIALISED, self::STATE_INITIALISED, self::LABEL_INITIALISED);
        $this->addCustomOrderStatus(self::STATUS_IN_PROGRESS, self::STATE_IN_PROGRESS, self::LABEL_IN_PROGRESS);
    }

    protected function addCustomOrderStatus($status, $state, $label)
    {
        $statusResource = $this->statusResourceFactory->create();
        $statut = $this->statusFactory->create();
        $statut->setData([
            'status' => $status,
            'label' => $label
        ]);
        try {
            $statusResource->save($statut);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $statut->assignState($state, true, true);
    }
}
