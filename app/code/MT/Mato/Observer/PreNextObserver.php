<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MT\Mato\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Catalog Compare Item Model
 *
 */
class PreNextObserver implements ObserverInterface
{

    protected $_session;

    public function __construct(
        \Magento\Framework\Session\SessionManager $session
    )
    {
        $this->_session = $session;
    }


    public function execute(\Magento\Framework\Event\Observer $observer){
        return;
        if ($observer->getEvent()->getRequest()->getControllerName() == 'view') {

            $products = $observer->getEvent()->getBlock('\Magento\Catalog\Block\Product\List')
                ->getLoadedProductCollection()
                ->getColumnValues('entity_id');
            $this->_session->setPrevNextProductCollection($products);
            unset($products);
        }
        return $this;
    }

}
