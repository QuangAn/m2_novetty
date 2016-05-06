<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Filter\Helper;

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
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        CustomerSession $customerSession
    ) {
        $this->_resource = $resource;
        $this->_customerSession = $customerSession;
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
        $this->_jsonEncoder = $jsonEncoder;
        parent::__construct($context);
    }

    /**
     * Get theme's main settings (single option)
     *
     * @return string
     */
    public function getConfig($optionString = '')
    {
        if($optionString) return $this->scopeConfig->getValue($optionString);
        else {
            $module = $this->_getRequest()->getModuleName();
            $enableFilter = false;
            if($module != 'catalog' && $module != 'catalogsearch') $enableFilter =  true;
            $bar  = $this->getConfig('mtfilter/general/bar');
            if($module != 'catalog' && $module != 'catalogsearch') $bar = false;
            return $this->_jsonEncoder->encode(
                array(
                    'mainDOM'   => trim($this->getConfig("mtfilter/{$module}/main_selector")),
                    'layerDOM'  => trim($this->getConfig("mtfilter/{$module}/layer_selector")),
                    'enable'    => $enableFilter ? $enableFilter : (bool)$this->getConfig("mtfilter/{$module}/enable"),
                    'bar'       => (bool)$bar,
                )
            );
        }
    }

    /**
     * Get theme's main settings design (single option)
     *
     * @return array
     */
    public function isPriceEnable($storeId)
    {
        $module = $this->_getRequest()->getModuleName();
        if($module != 'catalog' && $module != 'catalogsearch') return true;
        return $this->getConfig("mtfilter/{$module}/price");
    }


    public function buildUrl(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $value)
    {
        $result = [];
        $data = $this->_request->getParam($filter->getRequestVar());
        if(!empty($data)){
            $values = explode('-',$data);
            foreach($values as $key=>$val){
                if(empty($val)){
                    unset($values[$key]);
                }
            }
            $key = array_search($value, $values);

            if ($this->_isMultiselectAllowed($filter)) {
                if($key !== false) {
                    unset($values[$key]);
                    $result = $values;
                }else{
                    $result = $values;
                    $result[] = $value;
                }
            } else {
                if($key !== false) {
                    $result = [];
                } else {
                    $result[] = $value;
                }
            }
        } else {
            $result = [$value];
        }
        if(!empty($result)){
            $result = implode('-',$result);
        }else{
            $result = null;
        }


        $query[$filter->getRequestVar()] = $result;

        return $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

    private function _isMultiselectAllowed(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        return true;
//        $setting = $this->filterSettingHelper->getSettingByLayerFilter($filter);
//        return $setting->isMultiselect();
    }

}
