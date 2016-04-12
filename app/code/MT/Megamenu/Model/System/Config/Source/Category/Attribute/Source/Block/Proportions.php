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
namespace MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block;

class Proportions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{	
	/**
     * Get list of available block column proportions
     */
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '',		'label' => ' '),
                array('value' => '1',		'label' => '1'),
				array('value' => '2',		'label' => '2'),
				array('value' => '3',		'label' => '3'),
				array('value' => '4',		'label' => '4'),
				array('value' => '5',		'label' => '5'),
				array('value' => '6',		'label' => '6')
			);
        }
		return $this->_options;
    }
}
