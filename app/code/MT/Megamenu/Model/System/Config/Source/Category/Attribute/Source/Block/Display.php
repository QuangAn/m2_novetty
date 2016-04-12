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

class Display extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '1',		'label' => 'Yes'),
                array('value' => '0',		'label' => 'No')
			);
        }
		return $this->_options;
    }
}
