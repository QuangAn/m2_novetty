<?php
namespace MT\Mato\Model\System\Config;
class Newlabel implements \Magento\Framework\Option\ArrayInterface {
    public function toOptionArray()
    {

        $types = [
            ['value' => 'new', 'label' => __('New')],
            ['value' => 'imagenew', 'label' => __('Image')],
            ['value' => '', 'label' => __('No')],
        ];

        return $types;
    }
}