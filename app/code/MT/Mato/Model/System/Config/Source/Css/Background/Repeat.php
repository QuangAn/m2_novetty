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
namespace MT\Mato\Model\System\Config\Source\Css\Background;
class Repeat implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'no-repeat',	'label' => __('no-repeat')),
            array('value' => 'repeat',		'label' => __('repeat')),
            array('value' => 'repeat-x',	'label' => __('repeat-x')),
			array('value' => 'repeat-y',	'label' => __('repeat-y'))
        );
    }
}