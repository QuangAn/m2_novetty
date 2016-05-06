<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Model\Widget\Source\Parallax\Video;

class Layout implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'custom', 'label' => __('Custom'));
        $types[] = array('value' => 'fullwidth', 'label' => __('Full Width'));
        $types[] = array('value' => 'fullscreen', 'label' => __('Full Screen'));

        return $types;
    }
}
