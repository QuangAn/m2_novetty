<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category     Mato
 * @package      Mato Framework
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       ZooExtension.com
 * @email        magento@cleversoft.co
 * ------------------------------------------------------------------------------
 *
 */

namespace MT\Megamenu\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\Tree\Node;

use Magento\Catalog\Model\Category;

class Menu extends \Magento\Catalog\Block\Navigation
{
    protected $_isMomenu;

    protected $_isSmart;

    protected $_tplProcessor;

    /**
     * Array of level position counters
     *
     * @var array
     */
    protected $_itemLevelPositions = array();

    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $flatState;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    protected $_pageFactory;

//    public function __construct(Template\Context $context,
//                                  \Magento\Framework\ObjectManagerInterface $objectManager,
//                                  \Magento\Catalog\Model\Indexer\Category\Flat\State $state,
//                                  $data = [])
//    {
//        parent::__construct($context, $data);
//        $this->_objectManager = $objectManager;
//        $this->flatState = $state;
//    }

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $flatState,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Cms\Model\PageFactory $pageFactory,
        array $data = []

    ) {

        parent::__construct($context, $categoryFactory, $productCollectionFactory, $layerResolver, $httpContext, $catalogCategory, $registry, $flatState, $data);
        $this->_objectManager = $objectManager;
        $this->_pageFactory = $pageFactory;
    }

    protected function _renderCategoryMenuGroupItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
                                                        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false, $vertical = false)
    {
        $catdetail = $this->_getCatInfo($category->getId());

        if($vertical == false){
           if (!$category->getIsActive() || ($catdetail->getData('mtmenu_is_category')==0 && $level==0) ) return '';
            
        }

        if($vertical == true){
            if(!$category->getIsActive() || ($catdetail->getData('verticalmenu_is_category')==0 )) return '';
        }
        
        $html = array();

        // get all children
        if ($this->flatState->isFlatEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);
        $menutypes = $catdetail->getData('mtmenu_cat_groups');
        if($catdetail->getData('mtmenu_is_alignment') == 'justify'){
            $subwidth = '100%';
        }else{
            $subwidth = $catdetail->getData('mtmenu_is_width').'px';
        }

        // Check if show category block if no sub-category
        $showblock = false;
        $showblock = $hasActiveChildren;
        if ($this->_scopeConfig->getValue('mtmegamenu/show_if_no_children')) {$showblock = true; }
        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        if($level==1){
            $classes[] = 'groups item';
        }
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if($menutypes == 'drop_group'){
        	$classes[] = 'm-dropdown';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren && $level > 1) {
            $classes[] = 'mega_align_'.$catdetail->getData('mtmenu_is_alignment');
            $classes[] = 'parent';
        }
        if ($hasActiveChildren && $level==0 && $showblock) {
            $classes[] = 'mega_align_'.$catdetail->getData('mtmenu_is_alignment');
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
            $attributes['onmouseover'] = 'toggleMenu(this,1)';
            $attributes['onmouseout'] = 'toggleMenu(this,0)';
        }

        // assemble list item with attributes

        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;
        if ($level == 1 && $showblock){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_top')){
                $html[] = '<div class="mtmenu-block mtmenu-block-level1-top std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_top');
                $html[] = '</div>';
            }
        }
        $html[] = '<a href="'.$this->getCategoryUrl($category).'"'.$linkClass.'>';

        $labelCategory = $this->_getCategoryLabelHtml($catdetail, $level);
        if($level==1){
            $html[] = '<span class="title_group">' . $this->escapeHtml($category->getName()) .$labelCategory. '</span>';
        }else{
            $html[] = '<span>' . $this->escapeHtml($category->getName()) .$labelCategory. '</span>';
        }
        $html[] = '</a>';

        if ($level == 0) {
            $cat_block_right = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_right');
            $cat_block_left = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_left');
            if ($catdetail->getData('mtmenu_proportions_right') || $catdetail->getData('mtmenu_proportions_left')) {
                $columns = $catdetail->getData('mtmenu_cat_columns');
                $proportion_right = $catdetail->getData('mtmenu_proportions_right');
                $proportion_left = $catdetail->getData('mtmenu_proportions_left');
            } else {
                if($catdetail->getData('mtmenu_cat_columns')==''){
                    $columns = 4;
                }else{
                    $columns = $catdetail->getData('mtmenu_cat_columns');
                }
                $proportion_right = 1;
                $proportion_left = 1;
            }
            $goups = $proportion_right + $proportion_left;
            if (empty($cat_block_right) || empty($cat_block_left) || $menutypes == 'drop_group'){
                if(empty($cat_block_right)){
                    $gridCount1 = 'grid12-'.(12 - $proportion_left);
                    $gridCountLeft = 'grid12-' . ($proportion_left);
                }
                if(empty($cat_block_left)){
                    $gridCount1 = 'grid12-'.(12 - $proportion_right);
                    $gridCountRight = 'grid12-' . ($proportion_right);
                }
                if(empty($cat_block_right) && empty($cat_block_left)){
                    $gridCount1 = 'grid12-12';
                }
            } elseif (!$hasActiveChildren){
                $gridCountRight = 'grid12-'.$proportion_right;
                $gridCountLeft = 'grid12-'.$proportion_left;
            } else {
                $grid = 12 - $goups;
                $gridCount1 = 'grid12-' . ($grid);
                $gridCountRight = 'grid12-' . ($proportion_right);
                $gridCountLeft = 'grid12-' . ($proportion_left);
            }
            $goups = $proportion_right + $proportion_left;
        }

        // render children
        $li = '';
        $j = 0;
        $i = 0;
        foreach ($activeChildren as $child) {
            $li .= $this->_renderCategoryMenuGroupItemHtml(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
        }

        if ($childrenWrapClass && $showblock) {
            if($menutypes == 'drop_group'){
                if($level==1){
                    $html[] = '<div class="groups-wrapper">';
                }else{
                    $html[] = '<div class="level'.$level.' dropdown ' . $childrenWrapClass . ' shown-sub" data-width="'.$catdetail->getData('mtmenu_is_width').'" style="display:none; width: '.$subwidth.';height:auto;">';
                }

            }else{
                if($level==1){
                    $html[] = '<div class="groups-wrapper">';
                }else{
                    $html[] = '<div class="level'.$level.' ' . $childrenWrapClass . ' shown-sub" data-width="'.$catdetail->getData('mtmenu_is_width').'" style="display:none; width: '.$subwidth.'; height:auto;">';
                }
            }
        }
        if($level==0 && $showblock){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_top')){
                $html[] = '<div class="mtmenu-block mtmenu-block-top grid-full std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_top');
                $html[] = '</div>';
            }
            if($menutypes != 'drop_group'){
                if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_left') && $proportion_left){
                    $html[] = '<div class="menu-static-blocks mtmenu-block mtmenu-block-left '.$gridCountLeft.'">';
                    $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_left');
                    $html[] = '</div>';
                }
            }
        }
        if (!empty($li) && $hasActiveChildren) {
            if($level==0){
                $colCenter = 'itemgrid itemgrid-'. $columns .'col';
                $html[] = '<div class="mtmenu-block mtmenu-block-center menu-items '.$gridCount1.' '. $colCenter .'">';
            }
                    $html[] = '<ul class="level' . $level . '">';
                    $html[] = $li;
                    $html[] = '</ul>';
            if($level==0){
                $html[] = '</div>';
            }
        }

        if($level==0 && $showblock){
            if($menutypes != 'drop_group'){
                if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_right') && $proportion_right){
                    $html[] = '<div class="menu-static-blocks mtmenu-block mtmenu-block-right '.$gridCountRight.'">';
                    $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_right');
                    $html[] = '</div>';
                }
            }
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom')){
                $html[] = '<div class="mtmenu-block mtmenu-block-bottom grid-full std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom');
                $html[] = '</div>';
            }
        }
        if ($childrenWrapClass && $showblock) {
            $html[] = '</div>';
        }

        if ($level == 1 && $showblock){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom')){
                $html[] = '<div class="mtmenu-block mtmenu-block-level1-top std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom');
                $html[] = '</div>';
            }
        }
        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;

    }

    protected function _renderCategoryMenuItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
                                                   $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false, $vertical = false)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $catdetail = $this->_getCatInfo($category->getId());

        if($vertical == false) {
            if($catdetail->getData('mtmenu_is_category')==0 && $this->_isSmart == FALSE && $level==0){
                return '';
            } 
        }

        if($vertical == true) {
            if($catdetail->getData('verticalmenu_is_category')==0 && $this->_isSmart == FALSE && $level==0){
                return '';
            } 
        }

        /*if($catdetail->getData('mtmenu_is_category')==0 && $this->_isSmart == FALSE && $level==0 && $vertical = false 
            || $catdetail->getData('verticalmenu_is_category')==0 && $this->_isSmart == FALSE && $level==0 && $vertical = true){
            return '';
        }*/


        $html = array();

        // get all children
        if ($this->flatState->isFlatEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);
        $menutypes = $catdetail->getData('mtmenu_cat_groups');
        if($catdetail->getData('mtmenu_is_alignment') == 'justify'){
            $subwidth = '100%';
        }else{
            $subwidth = $catdetail->getData('mtmenu_is_width').'px';
        }
        // Check if show category block if no sub-category
        $showblock = $hasActiveChildren;
        if ($this->_scopeConfig->getValue('mtmegamenu/show_if_no_children')) {$showblock = true; }
        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        if($level==1){
            $classes[] = 'item';
        }
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if($menutypes == 'dropdown'){
            $classes[] = 'm-dropdown';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren && $level > 1) {
            $classes[] = 'mega_align_'.$catdetail->getData('mtmenu_is_alignment');
            $classes[] = 'parent';
        }
        if ($hasActiveChildren && $level==0 && $showblock && $this->_isMomenu == FALSE) {
            $classes[] = 'mega_align_'.$catdetail->getData('mtmenu_is_alignment');
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
            $attributes['onmouseover'] = 'toggleMenu(this,1)';
            $attributes['onmouseout'] = 'toggleMenu(this,0)';
        }

        // assemble list item with attributes
        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;
        if ($level == 1 && $showblock && $this->_isMomenu == FALSE){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_top')){
                $html[] = '<div class="mtmenu-block mtmenu-block-level1-top std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_top');
                $html[] = '</div>';
            }
        }
        $labelCategory = $this->_getCategoryLabelHtml($catdetail, $level);
        $html[] = '<a href="'.$this->getCategoryUrl($category).'"'.$linkClass.'>';
        $html[] = $catdetail->getData('mtmenu_is_icon');
        $html[] = '<span>' . $this->escapeHtml($category->getName()) . $labelCategory. '</span>';
        $html[] = '</a>';

        if ($level == 0 && $this->_isMomenu == FALSE) {
            $cat_block_right = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_right');
            $cat_block_left = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_left');
            if ($catdetail->getData('mtmenu_proportions_right') || $catdetail->getData('mtmenu_proportions_left')) {
                $columns = $catdetail->getData('mtmenu_cat_columns');
                $proportion_right = $catdetail->getData('mtmenu_proportions_right');
                $proportion_left = $catdetail->getData('mtmenu_proportions_left');
            } else {
                if($catdetail->getData('mtmenu_cat_columns')==''){
                    $columns = 4;
                }else{
                    $columns = $catdetail->getData('mtmenu_cat_columns');
                }
                $proportion_right = 1;
                $proportion_left = 1;
            }
            $goups = $proportion_right + $proportion_left;
            if (empty($cat_block_right) || empty($cat_block_left) || $menutypes == 'drop_group'){
                if(empty($cat_block_right)){
                    $gridCount1 = 'grid12-'.(12 - $proportion_left);
                    $gridCountLeft = 'grid12-' . ($proportion_left);
                }
                if(empty($cat_block_left)){
                    $gridCount1 = 'grid12-'.(12 - $proportion_right);
                    $gridCountRight = 'grid12-' . ($proportion_right);
                }
                if(empty($cat_block_right) && empty($cat_block_left)){
                    $gridCount1 = 'grid12-12';
                }
            } elseif (!$hasActiveChildren){
                $gridCountRight = 'grid12-'.$proportion_right;
                $gridCountLeft = 'grid12-'.$proportion_left;
            } else {
                $grid = 12 - $goups;
                $gridCount1 = 'grid12-' . ($grid);
                $gridCountRight = 'grid12-' . ($proportion_right);
                $gridCountLeft = 'grid12-' . ($proportion_left);
            }
            $goups = $proportion_right + $proportion_left;
        }

        // render children
        $li = '';
        $j = 0;
        $i = 0;
        foreach ($activeChildren as $child) {
            $li .= $this->_renderCategoryMenuItemHtml(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
        }


        if ($childrenWrapClass && $showblock && $this->_isMomenu == FALSE) {
            if($menutypes == 'dropdown'){
                $html[] = '<div class="dropdown ' . $childrenWrapClass . ' shown-sub"  data-width="'.$catdetail->getData('mtmenu_is_width').'"  style="display:none; width: '.$subwidth.'; height:auto;">';
            }else{
                $html[] = '<div class="' . $childrenWrapClass . ' shown-sub"  data-width="'.$catdetail->getData('mtmenu_is_width').'"  style="display:none; width: '.$subwidth.'; height:auto;">';
            }
        }

        if($level==0 && $showblock && $this->_isMomenu == FALSE){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_top')){
                $html[] = '<div class="mtmenu-block mtmenu-block-top grid-full std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_top');
                $html[] = '</div>';
            }
            if($menutypes != 'dropdown'){
                if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_left') && $proportion_left){
                    $html[] = '<div class="menu-static-blocks mtmenu-block mtmenu-block-left '.$gridCountLeft.'">';
                    $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_left');
                    $html[] = '</div>';
                }
            }
        }
        if (!empty($li) && $hasActiveChildren) {
            if($level == 0 && $this->_isMomenu == FALSE){
                $colCenter = 'itemgrid itemgrid-'. $columns .'col';
                $html[] = '<div class="mtmenu-block mtmenu-block-center menu-items '.$gridCount1.' '. $colCenter .'">';
            }
                $html[] = '<ul class="level' . $level . '">';
                $html[] = $li;
                $html[] = '</ul>';
            if($level==0 && $this->_isMomenu == FALSE){
                $html[] = '</div>';
            }
        }
        if($level==0 && $showblock && $this->_isMomenu == FALSE){
            if($menutypes != 'dropdown'){
                if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_right') && $proportion_right){
                    $html[] = '<div class="menu-static-blocks mtmenu-block mtmenu-block-right '.$gridCountRight.'">';
                    $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_right');
                    $html[] = '</div>';
                }
            }
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom')){
                $html[] = '<div class="mtmenu-block mtmenu-block-bottom grid-full std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom');
                $html[] = '</div>';
            }
        }

        if ($childrenWrapClass && $showblock && $this->_isMomenu == FALSE) {
            $html[] = '</div>';
        }

        if ($level == 1 && $showblock && $this->_isMomenu == FALSE){
            if($this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom') && $menutypes != 'dropdown'){
                $html[] = '<div class="mtmenu-block mtmenu-block-level1-top std">';
                $html[] = $this->_getCatBlock($catdetail, 'mtmenu_cat_block_bottom');
                $html[] = '</div>';
            }
        }
        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;

    }

    public function renderCategoriesMenuHtml($momenu = FALSE, $smart = TRUE, $level = 0, $outermostItemClass = '', $childrenWrapClass = '', $vertical = FALSE)
    {
        $this->_isMomenu = $momenu;
        $this->_isSmart = $smart;
        $activeCategories = array();
        $categoryHelper = $this->_objectManager->create('Magento\Catalog\Helper\Category');
        foreach ($categoryHelper->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $html = '';
        $j = 0;
        foreach ($activeCategories as $category) {
            if($this->_isMomenu){
                $html .= $this->_renderCategoryMenuItemHtml(
                    $category,
                    $level,
                    ($j == $activeCategoriesCount - 1),
                    ($j == 0),
                    true,
                    $outermostItemClass,
                    $childrenWrapClass,
                    true
                );
            }else{
                $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
                $catdetail = $categoryModel->load($category->getId());
                $menutype = $catdetail->getData('mtmenu_cat_groups');

                switch ($menutype) {
                    case 'group':
                    case 'drop_group':
                        $html .= $this->_renderCategoryMenuGroupItemHtml(
                            $category,
                            $level,
                            ($j == $activeCategoriesCount - 1),
                            ($j == 0),
                            true,
                            $outermostItemClass,
                            $childrenWrapClass,
                            true,
                            $vertical
                        );
                        break;
                    case 'classic':
                    case 'dropdown':
                        $html .= $this->_renderCategoryMenuItemHtml(
                            $category,
                            $level,
                            ($j == $activeCategoriesCount - 1),
                            ($j == 0),
                            true,
                            $outermostItemClass,
                            $childrenWrapClass,
                            true,
                            $vertical
                        );
                        break;
                    default:
                        $html .= $this->_renderCategoryMenuGroupItemHtml(
                            $category,
                            $level,
                            ($j == $activeCategoriesCount - 1),
                            ($j == 0),
                            true,
                            $outermostItemClass,
                            $childrenWrapClass,
                            true
                        );
                        break;
                }
            }
            $j++;
        }

        return $html;
    }
    protected function _getCatBlock($category, $block){
        if (!$this->_tplProcessor){
            $this->_tplProcessor = $this->_objectManager->create('Magento\Cms\Model\Template\Filter');
        }
        return $this->_tplProcessor->filter( trim($category->getData($block)) );
    }
    protected function _getCategoryLabelHtml($category, $level){
        $labelCategory = $category->getData('mtmenu_cat_label');
        if ($labelCategory){

            $getLabel = trim($this->_scopeConfig->getValue('mtmegamenu/' . $labelCategory));
            if ($getLabel) {
                if ($level == 0){
                    return ' <span class="cat-label cat-label-'. $labelCategory .' pin-bottom">' . $getLabel . '</span>';
                }else{
                    return ' <span class="cat-label cat-label-'. $labelCategory .'">' . $getLabel . '</span>';
                }
            }
        }return '';
    }

    protected function _getCatInfo($categoryId)
    {
        $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
        return $categoryModel->load($categoryId);
    }

    public function getModel($modelPath)
    {
        return $this->_objectManager->create($modelPath);
    }

    /**
     * Return item position representation in menu tree
     *
     * @param int $level
     * @return string
     */
    protected function _getItemPosition($level)
    {
        if ($level == 0) {
            $zeroLevelPosition = isset($this->_itemLevelPositions[$level]) ? $this->_itemLevelPositions[$level] + 1 : 1;
            $this->_itemLevelPositions = array();
            $this->_itemLevelPositions[$level] = $zeroLevelPosition;
        } elseif (isset($this->_itemLevelPositions[$level])) {
            $this->_itemLevelPositions[$level]++;
        } else {
            $this->_itemLevelPositions[$level] = 1;
        }
        $position = array();
        for($i = 0; $i <= $level; $i++) {
            if (isset($this->_itemLevelPositions[$i])) {
                $position[] = $this->_itemLevelPositions[$i];
            }
        }
        return implode('-', $position);
    }

    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path);
    }

    public function getCmsIdentifier()
    {
        $page = $this->_pageFactory->create();
        return $page->getIdentifier();
    }

    public function getStoreManager()
    {
        return $this->_storeManager;
    }
}