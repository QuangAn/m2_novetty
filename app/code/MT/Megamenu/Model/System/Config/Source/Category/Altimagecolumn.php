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
namespace MT\Mato\Model\System\Config\Source\Category;

class Altimagecolumn implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => 'label', 'label' => __('Label')],
            ['value' => 'position', 'label' => __('Sort Order')],
        ];

        return $types;
    }

}