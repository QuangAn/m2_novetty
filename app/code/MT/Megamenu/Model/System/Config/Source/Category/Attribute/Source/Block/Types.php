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

class Types extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{	
	/**
     * Get list of available type
     */
	public function getAllOptions()
	{
		if (!$this->_options)
		{
            return array(
                array('value'=>'group', 'label'=>__('Group Style')),
                array('value'=>'classic', 'label'=>__('Classic Style')),
                array('value'=>'dropdown', 'label'=>__('Dropdown Style')),
                array('value'=>'drop_group', 'label'=>__('Dropdown/Group Style'))
            );
        }
		return $this->_options;
    }
}
