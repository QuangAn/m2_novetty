<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block;


class Widget extends \Magento\Catalog\Block\Product\AbstractProduct implements  \Magento\Widget\Block\BlockInterface {

    const DEFAULT_PRODUCTS_COUNT = 10;

    /**
     * Name of request parameter for page number value
     */
    const PAGE_VAR_NAME = 'np';

    /**
     * Default value for products per page
     */
    const DEFAULT_PRODUCTS_PER_PAGE = 5;

    /**
     * Default value whether show pager or not
     */
    const DEFAULT_SHOW_PAGER = false;

    /**
     * Instance of pager block
     *
     * @var \Magento\Catalog\Block\Product\Widget\Html\Pager
     */
    protected $pager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * Catalog data
     *
     * @var Data
     */
    protected $_helperData = null;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder
     * @param \Magento\CatalogWidget\Model\Rule $rule
     * @param \Magento\Widget\Helper\Conditions $conditionsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \MT\Widget\Helper\Data $helperData,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_helperData = $helperData;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addColumnCountLayoutDepend('empty', 6)
            ->addColumnCountLayoutDepend('1column', 5)
            ->addColumnCountLayoutDepend('2columns-left', 4)
            ->addColumnCountLayoutDepend('2columns-right', 4)
            ->addColumnCountLayoutDepend('3columns', 3);

        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG,
            ], ]);
    }

    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }

    protected function _getKenburnsImages(){
        $prefix = Mage::getBaseUrl('media');
        $images = $this->getData('kenburns_images');
        $out = array();

        if (!is_array($images)){
            $images = explode(',', $images);
        }

        foreach ($images as $image){
            if ($image){
                $out[] = strpos($image, 'http') !== false ? $image : $prefix . $image;
            }
        }

        if (count($out) == 1){
            $out[] = $out[0];
        }

        return $out;
    }



    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $conditions = $this->getData('conditions')
            ? $this->getData('conditions')
            : $this->getData('conditions_encoded');

        return [
            'CATALOG_PRODUCTS_LIST_WIDGET',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            intval($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1)),
            $this->getProductsPerPage(),
            $conditions
        ];
    }


    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        if ($this->hasData('limit')) {
            return $this->getData('limit');
        }

        if (null === $this->getData('limit')) {
            $this->setData('limit', self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData('limit');
    }



    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Catalog\Model\Product::CACHE_TAG];
    }

    /**
     * Get value of widgets' title parameter
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }


    public function renderCollection($type, $value, $template='widget/collection/collection.phtml'){
        /* @var $block MT_Widget_Block_Widget_Collection */

        $block = $this->getLayout()->createBlock('MT\Widget\Block\Widget\Collection');

        $block->setData(array(
            'row'           => $this->getConfig('row'),
            'column'        => $this->getConfig('column'),
            'carousel'      => $this->getConfig('scroll'),
            'collection'    => $this->_getProductCollection($type, $value)
        ));
        $block->setTemplate($template);

        return $block->toHtml();
    }


    protected function _getProductCollection($type, $value){
        $params = [];

        if ($this->getData('category_ids')){
            $params['category_ids'] = explode(',', $this->getData('category_ids'));
        }
        if ($this->getData('product_ids')){
            $params['product_ids'] = explode(',', $this->getData('product_ids'));
        }

        $this->_productCollection = $this->_helperData->getProducts($type, $value, $params, $this->getData('limit'));

        return $this->_productCollection;
    }

    public function getBlocks(){
        $blocks     = [];
        $layout     = $this->getLayout();
        $storeId    = $this->_helperData->getStoreManager()->getStore()->getId();

        $classes    = [];
        $order      = [];


        foreach (array('lg', 'md', 'sm', 'xs') as $l){
            foreach (explode('|', $this->getData('block_' . $l)) as $block){
                list($blockId, $column, $cls) = explode(',', $block);

                if (!isset($classes[$blockId])){
                    $classes[$blockId] = "col-{$l}-{$column} ";
                }else{
                    $classes[$blockId] .= "col-{$l}-{$column} ";
                }
                $classes[$blockId] .= "{$cls} ";

                if (!in_array($blockId, $order)){
                    $order[] = $blockId;
                }
            }
        }

        foreach ($order as $blockId){
            /* @var $collection Mage_Cms_Model_Resource_Block_Collection */
            $collection = $this->_objectManager->create('Magento\Cms\Model\ResourceModel\Block\Collection')
                ->addFieldToFilter('block_id', array('eq' => $blockId));

            if ($collection->count()){
                /* @var $block Mage_Cms_Model_Block */
                $block = $collection->getFirstItem();
                $block->load($block->getId());
                $storeIds = $block->getStoreId();
                if ($block->getIsActive() && (in_array($storeId, $storeIds) || in_array(0, $storeIds))){
                    $blocks[] = array(
                        'class'     => isset($classes[$blockId]) ? $classes[$blockId] : '',
                        'content'   => $layout->createBlock('Magento\Cms\Block\Block')->setStoreId()->setBlockId($blockId)->toHtml()
                    );
                }
            }
        }

        return $blocks;
    }

    public function getAttibuteOptions(){
        $showOptions = explode(',', $this->getData('attribute_options'));
        list($attributeId, $attributeCode) = explode(',' , $this->getData('attribute'));

        $optionCollection = $this->_objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')
            ->setAttributeFilter($attributeId)
            ->setStoreFilter()
            ->load();

        $options = array();
        foreach ($optionCollection as $option){
            if (in_array($option->getId(), $showOptions)){
                if ($this->getData('attribute_link')){
                    $options[] = array(
                        'id'    => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage() ?
                            (
                            strpos($option->getImage(), 'http') === 0 ?
                                $option->getImage() :
                                $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $option->getImage()
                            ):
                            null,
                        'link'  => $this->getUrl('catalogsearch/result/index', array('q' => $option->getValue()))
                    );
                }else{
                    $options[] = array(
                        'id'    => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage() ?
                            (
                            strpos($option->getImage(), 'http') === 0 ?
                                $option->getImage() :
                                $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $option->getImage()
                            ):
                            null
                    );
                }
            }
        }

        return $options;
    }

    public function getTabs(){
        $tabs = array();
        $cats = array();
        $type = $this->getData('widget_tab');
        $labels = explode('||', $this->getData('labels'));

        switch ($type){
            case 'categories':
                $categoryIds = explode(',', $this->getData('category_ids'));
                foreach ($categoryIds as $index => $categoryId){
                    $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
                    $categoryModel = $categoryModel->load($categoryId, array('name'));
                    if ($categoryModel->getId()){
                        $tabs[] = array(
                            'type'  => 'category',
                            'id'    => 'category-' . $categoryModel->getId(),
                            'label' => isset($labels[$index]) && $labels[$index] ? $labels[$index] : $categoryModel->getName(),
                            'value' => $categoryModel->getId()
                        );
                    }
                }
                break;
            case 'collections':
                $collectionNames = $this->getData('collections');
                if (!is_array($collectionNames)) $collectionNames = explode(',', $this->getData('collections'));
                foreach ($collectionNames as $index => $collectionName){
                    $tabs[] = array(
                        'type'  => 'collection',
                        'id'    => 'collection-' . $collectionName,
                        'label' => isset($labels[$index]) && $labels[$index] ? $labels[$index] : $collectionName,
                        'value' => $collectionName
                    );
                }

                $categoryIds = explode(',', $this->getData('category_ids'));
                foreach ($categoryIds as $index => $categoryId){
                    $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
                    $categoryModel = $categoryModel->load($categoryId, array('name'));
                    if ($categoryModel->getId()){
                        $cats[] = array(
                            'type'  => 'category',
                            'id'    => 'category-' . $categoryModel->getId(),
                            'label' => isset($labels[$index]) && $labels[$index] ? $labels[$index] : $categoryModel->getName(),
                            'value' => $categoryModel->getId()
                        );
                    }
                }

                break;
        }

        return $tabs;
    }


    public function getConfig($name, $param=null){
        /* @var $helper Mage_Core_Helper_Data */

        switch ($name){
            case 'countdown':
                return $this->_jsonEncoder->encode(array(
                    'enable'            => (bool) $this->getData('countdown'),
                    'yearText'          => __('years'),
                    'monthText'         => __('months'),
                    'weekText'          => __('weeks'),
                    'dayText'           => __('days'),
                    'hourText'          => __('hours'),
                    'minText'           => __('mins'),
                    'secText'           => __('secs'),
                    'yearSingularText'  => __('year'),
                    'monthSingularText' => __('month'),
                    'weekSingularText'  => __('week'),
                    'daySingularText'   => __('day'),
                    'hourSingularText'  => __('hour'),
                    'minSingularText'   => __('min'),
                    'secSingularText'   => __('sec'),
                    'engineSrc'         => $this->getViewFileUrl('MT_Extensions::js/jquery/plugins/jquery.jcountdown.min.js')
                ));
                break;
            case 'kenburns':
                return $this->_jsonEncoder->encode(array(
                    'enable'    => $this->getData('background') == 'kenburns',
                    //'images'    => $this->_getKenburnsImages(),
                    'overlay'   => $this->getData('background_overlay'),
                    'engineSrc' => $this->getViewFileUrl('MT_Extensions::js/jquery/plugins/kenburns.js')
                ));
                break;
            case 'parallax':
                return $this->_jsonEncoder->encode(array(
                    'enable'    => $this->getData('background') == 'parallax',
                    'type'      => $this->getData('parallax_type'),
                    'overlay'   => $this->getData('background_overlay'),
                    'video'     => array(
                        'src'       => $this->getData('parallax_video_src'),
                        'volume'    => (bool) $this->getData('parallax_video_volume'),
                    ),
                    'image'     => array(
                        'src'       => $this->getData('parallax_image_src') ?
                            (
                            strpos($this->getData('parallax_image_src'), 'http') === 0 ?
                                $this->getData('parallax_image_src') :
                                $this->getViewFileUrl($this->getData('parallax_image_src'))
                            ):
                            null,
                        'fit'       => $this->getData('parallax_image_fit'),
                        'repeat'    => $this->getData('parallax_image_repeat')
                    ),
                    'file'      => array(
                        'poster'    => $this->getData('parallax_video_poster') ?
                            (
                            strpos($this->getData('parallax_video_poster'), 'http') === 0 ?
                                $this->getData('parallax_video_poster') :
                                $this->getViewFileUrl($this->getData('parallax_video_poster'))
                            ):
                            null,
                        'mp4'       => $this->getData('parallax_video_mp4') ?
                            (
                            strpos($this->getData('parallax_video_mp4'), 'http') === 0 ?
                                $this->getData('parallax_video_mp4') :
                                $this->getViewFileUrl($this->getData('parallax_video_mp4'))
                            ):
                            null,
                        'webm'      => $this->getData('parallax_video_webm') ?
                            (
                            strpos($this->getData('parallax_video_webm'), 'http') === 0 ?
                                $this->getData('parallax_video_webm') :
                                $this->getViewFileUrl($this->getData('parallax_video_webm'))
                            ):
                            null,
                        'volume'    => (bool) $this->getData('parallax_video_volume')
                    )
                ));
                break;
            case 'carousel':
                return $this->_jsonEncoder->encode(array(
                    'enable'        => (bool) $this->getData('scroll'),
                    'pagination'    => (bool) $this->getData('paging'),
                    'autoPlay'      => is_numeric($this->getData('autoplay')) ? (int) $this->getData('autoplay') : false,
                    'items'         => is_numeric($this->getData('column')) ? (int) $this->getData('column') : 4,
                    'singleItem'    => $this->getData('column') == 1,
                    'lazyLoad'      => true,
                    'lazyEffect'    => false,
                    'addClassActive'=> true,
                    'navigation'    => (bool) $this->getData('navigation'),
                    'navigationText'=> array($this->getData('navigation_prev'), $this->getData('navigation_next')),
                    'engineSrc'     => $this->getViewFileUrl('MT_Widget::js/owl-carousel/owl.carousel.js')
                ));
                break;
            case 'animation':
                return $this->_jsonEncoder->encode(array(
                    'enable'        => (bool) $this->getData('animate'),
                    'animationName' => $this->getData('animation'),
                    'animationDelay'=> is_numeric($this->getData('animation_delay')) ? (int) $this->getData('animation_delay') : 300,
                    'itemSelector'  => $this->getData('animate_item_selector'),
                ));
                break;
            case 'widget_title':
                return $this->escapeHtml($this->getData('widget_title'));
                break;
            case 'id':
                return $this->_mathRandom->getUniqueHash(is_string($param) ? $param : 'widget-');
                break;
            case 'row':
                return is_numeric($this->getData('row')) ? (int) $this->getData('row') : 1;
                break;
            case 'column':
                return is_numeric($this->getData('column')) ? (int) $this->getData('column') : 4;
                break;
            case 'limit':
                return is_numeric($this->getData('limit')) ? (int) $this->getData('limit') : 1;
                break;
            case 'classes':
                return $this->getData('classes') . ($this->getData('animate') ? ' ' . $this->getData('animation') : '');
                break;
            default:
                return $this->getData($name);
        }
    }
}
