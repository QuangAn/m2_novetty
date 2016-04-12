<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

class MT_Widget_Model_Widget_Source_Parallax_Video_Layout{
    public function toOptionArray(){
        $types[] = array('value' => 'custom', 'label' => Mage::helper('mtwidget')->__('Custom'));
        $types[] = array('value' => 'fullwidth', 'label' => Mage::helper('mtwidget')->__('Full Width'));
        $types[] = array('value' => 'fullscreen', 'label' => Mage::helper('mtwidget')->__('Full Screen'));

        return $types;
    }
}
