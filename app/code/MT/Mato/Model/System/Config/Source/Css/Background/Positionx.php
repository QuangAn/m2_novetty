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
class Positionx implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'left',	'label' => __('left')),
            array('value' => 'center',	'label' => __('center')),
            array('value' => 'right',	'label' => __('right'))
        );
    }
}