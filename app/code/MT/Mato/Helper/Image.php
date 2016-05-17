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
        if ($h <= 0)
        {

            return $url = $this->_catalogImageHelper
                ->init($product, $imgVersion)
                ->setImageFile($file)
                ->constrainOnly(false)
                ->keepAspectRatio(true)
                ->keepFrame(false)
                ->resize($w)
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
