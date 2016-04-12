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
namespace MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source;

class CategoryLabel extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
	protected $_options;

	public $scopeConfig;

	public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig){
		$this->scopeConfig = $scopeConfig;
	}

	/**
     * Get list of existing category labels
     */
	public function getAllOptions()
	{
		if (!$this->_options)
		{	
			$this->_options[] =
					array('value' => '', 'label' => " ");
					
			if ($tmp = trim($this->scopeConfig->getValue('mtmegamenu/menu/label1')))
			{
				$this->_options[] =
					array('value' => 'label1', 'label' => $tmp);
			}
			if ($tmp = trim($this->scopeConfig->getValue('mtmegamenu/menu/label2')))
			{
				$this->_options[] =
					array('value' => 'label2', 'label' => $tmp);
			}
        }
        return $this->_options;
    }
}
