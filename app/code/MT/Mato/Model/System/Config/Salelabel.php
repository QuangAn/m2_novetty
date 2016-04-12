<?php
namespace MT\Mato\Model\System\Config;
class Salelabel implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $types = [
            ['value' => 'sale', 'label' => __('Sale')],
            ['value' => 'imagesale', 'label' => __('Image')],
            ['value' => '', 'label' => __('No')],
        ];

        return $types;
    }
}