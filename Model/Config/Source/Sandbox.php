<?php

namespace Camoo\Enkap\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Sandbox implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('No')],
            ['value' => 1, 'label' => __('Yes')],
        ];
    }
}
