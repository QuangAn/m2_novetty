<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Model\Widget\Source\Parallax\Background;

class Overlay  implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'none', 'label' => __('None'));
        $types[] = array('value' => 'js/mt/widget/images/gridtile.png', 'label' => __('2 x 2 Black'));
        $types[] = array('value' => 'js/mt/widget/images/gridtile_white.png', 'label' => __('2 x 2 White'));
        $types[] = array('value' => 'js/mt/widget/images/gridtile_3x3.png', 'label' => __('3 x 3 Black'));
        $types[] = array('value' => 'js/mt/widget/images/gridtile_3x3_white.png', 'label' => __('3 x 3 White'));

        return $types;
    }
}
