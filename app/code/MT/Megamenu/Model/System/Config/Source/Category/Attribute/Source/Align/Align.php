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


namespace MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Align;

class Align extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => 'left',		'label' => 'Left'),
                array('value' => 'right',		'label' => 'Right'),
                array('value' => 'center',		'label' => 'Center'),
                array('value' => 'justify',		'label' => 'Justify'),
			);
        }
		return $this->_options;
    }
}
