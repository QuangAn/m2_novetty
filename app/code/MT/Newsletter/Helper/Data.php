<?php
/**
 * @category    MT
 * @package     MT_Newsletter
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Newsletter\Helper;

use Magento\Customer\Model\Session as CustomerSession;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{


    public function getCfgEnable()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/enable');
    }
    public function getCfgWidth()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/width');
    }
    public function getCfgHeight()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/height');
    }
    public function getCfgBackgroundColor()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/background_color');
    }
    public function getCfgBackgroundImage()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/background_image');
    }
    public function getCfgIntro()
    {
        return $this->scopeConfig->getValue('mtnewsletter/display_options/intro');
    }
}
