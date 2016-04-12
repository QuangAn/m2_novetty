<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category Mato
 * @package Mato Framework
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       ZooExtension.com
 * @email        magento@cleversoft.co
 * ------------------------------------------------------------------------------
 *
 */
namespace MT\Mato\Model\System\Config\Source\Layout\Element;
class Displayonhover implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => '0', 'label' => __('Don\'t Display')],
            ['value' => '1', 'label' => __('Display On Hover')]
        ];

        return $types;
    }

}