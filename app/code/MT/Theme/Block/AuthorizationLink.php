<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MT\Theme\Block;



class AuthorizationLink extends \Magento\Customer\Block\Account\AuthorizationLink
{
	public function getLabel()
    {
        return $this->isLoggedIn() ? __('Log Out') : __('Login');
    }
}
