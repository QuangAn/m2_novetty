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
namespace MT\Mato\Model\System\Config\Source\Mainmenu;

class Menuleftanimation implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $types = [
            ['value' => 'show', 'label' => __('Show/Hide')],
            ['value' => 'slide', 'label' => __('Slide')],
            ['value' => 'slideWidth', 'label' => __('Slide Width')],
            ['value' => 'fade', 'label' => __('Fade')]
        ];

        return $types;
    }

}
