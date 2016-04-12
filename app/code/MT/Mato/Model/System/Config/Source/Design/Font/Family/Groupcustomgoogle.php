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

namespace MT\Mato\Model\System\Config\Source\Design\Font\Family;
class Groupcustomgoogle implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'custom', 'label' => __('Custom...')),
			array('value' => 'google', 'label' => __('Google Fonts...')),
			array('value' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 'label' => __('Arial, "Helvetica Neue", Helvetica, sans-serif')),
			array('value' => 'Georgia, serif', 'label' => __('Georgia, serif')),
			array('value' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif', 'label' => __('"Lucida Sans Unicode", "Lucida Grande", sans-serif')),
			array('value' => '"Palatino Linotype", "Book Antiqua", Palatino, serif', 'label' => __('"Palatino Linotype", "Book Antiqua", Palatino, serif')),
			array('value' => 'Tahoma, Geneva, sans-serif', 'label' => __('Tahoma, Geneva, sans-serif')),
			array('value' => '"Trebuchet MS", Helvetica, sans-serif', 'label' => __('"Trebuchet MS", Helvetica, sans-serif')),
			array('value' => 'Verdana, Geneva, sans-serif', 'label' => __('Verdana, Geneva, sans-serif')),
        );
    }
}