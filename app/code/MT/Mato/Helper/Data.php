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

use Magento\Customer\Model\Session as CustomerSession;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{


    /**
     * Resource instance
     *
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Initialize dependencies
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * Initialize dependencies
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_coreSession;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;


    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $viewedModel;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_localeDate;

    /**
     * @var \MT\Mato\Helper\Image
     */
    protected $_imageHelper;

    /**
     * @var \Magento\Framework\View\ConfigInterface
     */
    protected $_viewConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Reports\Model\Product\Index\Viewed $viewedModel,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \MT\Mato\Helper\Image $imageHelper,
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Magento\Framework\Session\SessionManager $coreSession,
        CustomerSession $customerSession
    ) {
        $this->_resource = $resource;
        $this->_customerSession = $customerSession;
        $this->_coreSession = $coreSession;
        $this->storeManager = $storeManager;
        $this->_coreRegistry = $registry;
        $this->_checkoutSession = $checkoutSession;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_catalogConfig = $catalogConfig;
        $this->_objectManager = $objectManager;
        $this->_localeDate = $localeDate;
        $this->viewedModel = $viewedModel;
        $this->_imageHelper = $imageHelper;
        $this->_viewConfig = $viewConfig;
        parent::__construct($context);
    }

    /**
     * Get theme's main settings (single option)
     *
     * @return string
     */
    public function getCfg($optionString, $store = null)
    {
        return $this->scopeConfig->getValue('mtmato/' . $optionString);
    }

    /**
     * Get theme's main settings design (single option)
     *
     * @return array
     */
    public function getCfgSectionDesign($storeId)
    {
        if ($storeId)
            return $this->scopeConfig->getValue('mtmato_design', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        else
            return $this->scopeConfig->getValue('mtmato_design');
    }

    /**
     * Get theme's design settings (single option)
     *
     * @return string
     */
    public function getThemeDesignCfg($optionString, $storeCode = NULL)
    {
        return $this->scopeConfig->getValue('mtmato_design/' . $optionString, $storeCode);
    }

    /**
     * Get theme's layout settings (single option)
     *
     * @return string
     */
    public function getThemeLayoutCfg($optionString, $storeCode = NULL)
    {
        return $this->scopeConfig->getValue('mtmato_layout/' . $optionString, $storeCode);
    }

    /**
     * Get config show label for product
     *
     * @return html label
     */
    public function getLabel(\Magento\Catalog\Model\Product $product)
    {
        if ( !$product instanceof  \Magento\Catalog\Model\Product )
            return ;
        $html = '';
        if (!$this->getCfg("product_labels/new") &&
            !$this->getCfg("product_labels/sale")) {
            return $html;
        }
        if ( $this->getCfg("product_labels/new") && $this->_checkNew($product) ) {
            $html .= '<div class="product-new-label">'.$this->__('New').'</div>';
        }
        if ( $this->getCfg("product_labels/sale") && $this->_checkSale($product) ) {
            $html .= '<div class="product-sale-label">'.$this->__('Sale').'</div>';
        }

        return $html;
    }

    /**
     * Get date from new product
     *
     * @return from date and to date
     */
    protected function _checkNew($product)
    {
        $from = strtotime($product->getData('news_from_date'));
        $to = strtotime($product->getData('news_to_date'));

        return $this->_checkDate($from, $to);
    }

    /**
     * Get date from sale product
     *
     * @return from date and to date
     */
    protected function _checkSale($product)
    {
        $from = strtotime($product->getData('special_from_date'));
        $to = strtotime($product->getData('special_to_date'));

        return $this->_checkDate($from, $to);
    }

    /**
     * Check date time locale
     *
     * @return true or false
     */
    protected function _checkDate($from, $to)
    {
        $today = strtotime(
            $this->_localeDate->date()->setTime(0,0,0)
                ->format(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT)
        );

        if ($from && $today < $from) {
            return false;
        }
        if ($to && $today > $to) {
            return false;
        }
        if (!$to && !$from) {
            return false;
        }
        return true;
    }

    /**
     * Get type for product
     *
     * @return true or false
     */
    public function getType(\Magento\Catalog\Model\Product $product)
    {
        if ( 'Product' != get_class($product) )
            return;
        foreach ($product->getOptionsType() as $o) {
            $optionType = $o['type'];
            if ($optionType == 'file') {
                return true;
            }
        }
        return false;
    }


    /**
     * Get alternative image HTML of the given product
     *
     * @param Product	$product		Product
     * @param int							$w				Image width
     * @param int							$h				Image height
     * @param string						$imgVersion		Image version: image, small_image, thumbnail
     * @return string
     */
    public function getAltImgHtml($product, $w, $h, $imgVersion='small_image')
    {
        if($w == '' || $h == ''){
            $viewImageConfig = $this->_viewConfig->getViewConfig()->getMediaAttributes('Magento_Catalog', 'images', $imgVersion) ;
            $w = $viewImageConfig['width'];
            $h = $viewImageConfig['height'];
        }
        $ratio = 1;
        if ($w && $h) {
            $ratio =  $h / $w;
        }

        $product->load('media_gallery_images');
        $images = $product->getMediaGalleryImages();
        $column = $this->getCfg('category/alt_image_column');
        $value = $this->getCfg('category/alt_image_column_value');
        if ($images instanceof \Magento\Framework\Data\Collection) {
            if ($altImg = $images->getItemByColumnValue($column, $value))
            {
                $html =
                    '<span class="product-image-wrapper" style="padding-bottom: '. ($ratio * 100) .'%">' .
                    '<img class="img-responsive alt-img lazy" src="' . $this->_imageHelper->getImg($product, $w, $h, $imgVersion, $altImg->getFile()) . '" alt="' . $product->getName() . '" />' .
                    '</span>';
                return $html;
            }
        }
    }

    public function getPreviousProduct()
    {
        $currentProduct = $this->_coreRegistry->registry('current_product');

        if (!$currentProduct) {
            return false;
        }

        $prodId = $currentProduct->getId();

        //$positions = $this->_coreSession->getPrevNextProductCollection();
        //if (!$positions) {
            $currentCategory = $this->_coreRegistry->registry('current_category');

            if (!$currentCategory) {
                $categoryIds = $currentProduct->getCategoryIds();
                $categoryId = current($categoryIds);
                $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
                $currentCategory = $categoryModel->load($categoryId);

                $this->_coreRegistry->register('current_category', $currentCategory);
            }

            $positions = array_reverse(array_keys($this->_coreRegistry->registry('current_category')->getProductsPosition()));
        //}



        $cpk = @array_search($prodId, $positions);

        $slice = array_reverse(array_slice($positions, 0, $cpk));

        $modelProduct = $this->_objectManager->create('Magento\Catalog\Model\Product');
        foreach ($slice as $productId) {
            $product = $modelProduct->load($productId);

            if ($product && $product->getId() && $product->isVisibleInCatalog() && $product->isVisibleInSiteVisibility()) {
                return $product;
            }
        }



        return false;
    }

    public function getNextProduct()
    {
        $currentProduct = $this->_coreRegistry->registry('current_product');

        if (!$currentProduct) {
            return false;
        }

        $prodId = $currentProduct->getId();

        //$positions = $this->_coreSession->getPrevNextProductCollection();

        //if (!$positions) {
            $currentCategory = $this->_coreRegistry->registry('current_category');
            if (!$currentCategory) {
                $categoryIds = $currentProduct->getCategoryIds();
                $categoryId = current($categoryIds);

                $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
                $currentCategory = $categoryModel->load($categoryId);

                $this->_coreRegistry->register('current_category', $currentCategory);
            }

            $positions = array_reverse(array_keys($this->_coreRegistry->registry('current_category')->getProductsPosition()));
        //}


        $cpk = @array_search($prodId, $positions);

        $slice = array_slice($positions, $cpk + 1, count($positions));

        $modelProduct = $this->_objectManager->create('Magento\Catalog\Model\Product');
        foreach ($slice as $productId) {
            $product = $modelProduct->load($productId);

            if ($product && $product->getId() && $product->isVisibleInCatalog() && $product->isVisibleInSiteVisibility()) {
                return $product;
            }
        }

        return false;
    }

}
