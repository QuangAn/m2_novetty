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
class Ratioimage implements \Magento\Framework\Option\ArrayInterface{


    public function toOptionArray()
    {
        $types = [
            ['value' => '1:1', 'label' => __('Square Rectangle')],
            ['value' => '3:4', 'label' => __('Horizontal Rectangle')],
            ['value' => '4:3', 'label' => __('Vertical Rectangle')]
        ];

        return $types;
    }

}