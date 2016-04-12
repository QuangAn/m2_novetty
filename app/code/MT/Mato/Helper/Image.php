<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Mato\Helper;

class Image extends \Magento\Framework\App\Helper\AbstractHelper{

    protected $_catalogImageHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Helper\Image $imageHelper
    ){
        parent::__construct($context);
        $this->_catalogImageHelper = $imageHelper;
    }

    public function getImg($product, $w, $h, $imgVersion='image', $file=NULL)
    {
        $ratiocf = $this->scopeConfig->getValue('mtmato/category/aspect_ratio_size');
        if ($h <= 0)
        {
            $w_new = $w ? $w : '280';
            switch ($ratiocf) {
                case '1:1':
                    $new_h = $w_new;
                    break;
                case '3:4':
                    $new_h = round($w_new*(3/4));
                    break;
                case '4:3':
                    $new_h = round($w_new*(4/3));
                    break;
            }
            return $url = $this->_catalogImageHelper
                ->init($product, $imgVersion)
                ->setImageFile($file)
                ->resize($w_new, $new_h)
                ->getUrl();
        }
        else
        {
            return $url = $this->_catalogImageHelper
                ->init($product, $imgVersion)
                ->setImageFile($file)
                ->resize($w, $h)
                ->getUrl();
        }
    }

}
