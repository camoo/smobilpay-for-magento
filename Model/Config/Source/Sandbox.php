<?php

namespace Camoo\Enkap\Model\Config\Source;

class Sandbox implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('No')],
            ['value' => 1, 'label' => __('Yes')],
        ];
    }
}