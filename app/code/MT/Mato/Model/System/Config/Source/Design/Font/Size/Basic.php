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
namespace MT\Mato\Model\System\Config\Source\Design\Font\Size;
class Basic implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return array(
			array('value' => '12px',	'label' => __('12 px')),
			array('value' => '13px',	'label' => __('13 px')),
            array('value' => '14px',	'label' => __('14 px'))
        );
    }
}