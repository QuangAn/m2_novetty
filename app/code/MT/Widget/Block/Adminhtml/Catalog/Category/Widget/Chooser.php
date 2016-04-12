<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block\Adminhtml\Catalog\Category\Widget;


class Chooser extends \Magento\Catalog\Block\Adminhtml\Category\Widget\Chooser{

    protected $_template = 'widget/catalog/category/widget/tree.phtml';

    protected function _getNodeJson($node, $level = 0)
    {
        $item = parent::_getNodeJson($node, $level);
        if (in_array($node->getId(), $this->getSelectedCategories())) {
            $item['checked'] = true;
        }
        $item['is_anchor'] = 0;
        $item['url_key'] = $node->getData('url_key');
        return $item;
    }

}