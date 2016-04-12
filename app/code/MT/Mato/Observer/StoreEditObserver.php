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
class StoreEditObserver implements ObserverInterface
{

    protected $_cssGenerator;

    public function __construct(
        \MT\Mato\Model\Cssgen\Generator $generator
    )
    {
        $this->_cssGenerator = $generator;
    }

    /**
     * After store view is saved
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $store = $observer->getEvent()->getStore();
        $storeCode = $store->getCode();
        $websiteCode = $store->getWebsite()->getCode();

        $this->_cssGenerator->generateCss('design', $websiteCode, $storeCode);
        $this->_cssGenerator->generateCss('layout', $websiteCode, $storeCode);
    }

}
