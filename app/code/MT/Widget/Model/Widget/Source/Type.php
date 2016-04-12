<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Model\Widget\Source;


class Type implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types = [['value' => 'product', 'label' => __('Product')],['value' => 'block', 'label' => __('Block')],['value' => 'attribute', 'label' => __('Attribute')],
                ['value' => 'blog', 'label' => __('Blog')]];

        return $types;
    }
}
