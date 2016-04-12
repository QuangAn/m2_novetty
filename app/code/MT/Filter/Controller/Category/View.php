<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MT\Filter\Controller\Category;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Catalog\Controller\Category\View
{
    /**
     * Product view action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if ($this->_request->getParam(\Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED)) {
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl());
        }
        $category = $this->_initCategory();
        if ($category) {
            $settings = $this->_catalogDesign->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $this->_catalogDesign->applyCustomDesign($settings->getCustomDesign());
            }

            $this->_catalogSession->setLastViewedCategoryId($category->getId());

            $page = $this->resultPageFactory->create();
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $page->getConfig()->setPageLayout($settings->getPageLayout());
            }
            if ($category->getIsAnchor()) {
                $type = $category->hasChildren() ? 'layered' : 'layered_without_children';
            } else {
                $type = $category->hasChildren() ? 'default' : 'default_without_children';
            }

            if (!$category->hasChildren()) {
                // Two levels removed from parent.  Need to add default page type.
                $parentType = strtok($type, '_');
                $page->addPageLayoutHandles(['type' => $parentType]);
            }
            $page->addPageLayoutHandles(['type' => $type, 'id' => $category->getId()]);

            // apply custom layout update once layout is loaded
            $layoutUpdates = $settings->getLayoutUpdates();
            if ($layoutUpdates && is_array($layoutUpdates)) {
                foreach ($layoutUpdates as $layoutUpdate) {
                    $page->addUpdate($layoutUpdate);
                }
            }

            $page->getConfig()->addBodyClass('page-products')
                ->addBodyClass('categorypath-' . $this->categoryUrlPathGenerator->getUrlPath($category))
                ->addBodyClass('category-' . $category->getUrlKey());

            if ($this->_request->isXmlHttpRequest() && $this->_request->getParam('isAjax') == true){
                $output['main'] = $this->_view->getLayout()->getBlock('category.products.list')->toHtml();               
                foreach($this->_view->getLayout()->getAllBlocks() as $block){
                    if ($block->getType() == 'Magento\LayeredNavigation\Block\Navigation'){
                        $output['layer'] = $block->toHtml();
                    }
                }

                $this->getResponse()->setHeader('Content-Type', 'application/json');
                $this->getResponse()->setBody($this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($output));

            }else{
                return $page;
            }

        } elseif (!$this->getResponse()->isRedirect()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
