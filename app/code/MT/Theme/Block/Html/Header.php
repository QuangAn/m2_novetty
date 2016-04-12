<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MT\Theme\Block\Html;


use Magento\Customer\Model\Session as CustomerSession;

/**
 * Html page header block
 */
class Header extends \Magento\Theme\Block\Html\Header
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'html/header.phtml';

    /**
     * @var CustomerSession
     */
    protected $_customerSession;


    public function __construct(
        CustomerSession $customerSession,
        \Magento\Framework\View\Element\Template\Context $context
    ){
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }


    /**
     * Retrieve welcome text
     *
     * @return string
     */
    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            $this->_data['welcome'] = $this->_scopeConfig->getValue(
                'design/header/welcome',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return $this->_data['welcome'];
    }

    public function getConfig($path){
        return $this->_scopeConfig->getValue($path);
    }

    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    public function getStoreManager()
    {
        return $this->_storeManager;
    }
}
