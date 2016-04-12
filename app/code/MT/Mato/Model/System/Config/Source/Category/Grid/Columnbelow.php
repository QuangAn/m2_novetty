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
namespace MT\Mato\Model\System\Config\Source\Category\Grid;
class Columnbelow implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {

        $types = [
            ['value' => '1', 'label' => __('1')],
            ['value' => '2', 'label' => __('2')],
            ['value' => '3', 'label' => __('3')]
        ];

        return $types;
    }

}