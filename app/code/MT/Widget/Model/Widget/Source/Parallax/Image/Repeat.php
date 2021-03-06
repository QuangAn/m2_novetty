<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Model\Widget\Source\Parallax\Image;

class Repeat  implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types[] = array('value' => 'no-repeat',    'label' => 'no-repeat');
        $types[] = array('value' => 'repeat',       'label' => 'repeat');
        $types[] = array('value' => 'repeat-x',     'label' => 'repeat-x');
        $types[] = array('value' => 'repeat-y',     'label' => 'repeat-y');

        return $types;
    }
}