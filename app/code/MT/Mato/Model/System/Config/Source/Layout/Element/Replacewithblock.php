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
class Replacewithblock implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => '0', 'label' => __('Disable Completely')],
            ['value' => '1', 'label' => __('Don\'t Replace With Static Block')],
            ['value' => '2', 'label' => __('If Empty, Replace With Static Block')],
            ['value' => '3', 'label' => __('Replace With Static Block')]
        ];

        return $types;
    }

}