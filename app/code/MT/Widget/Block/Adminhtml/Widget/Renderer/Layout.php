<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block\Adminhtml\Widget\Renderer;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Layout extends Template{
    public function prepareElementHtml(AbstractElement $element){
        $html = "

<script type='text/javascript'>
    require([
        'MT_Widget/js/widget',
        'prototype'
    ], function(){

        document.observe('dom:loaded', function(){
            window.layout_{$this->getData("target")}_object = new MTLayoutPreview('layout_{$this->getFieldsetId()}_preview_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
        });
        setTimeout(function(){
            window.layout_{$this->getData("target")}_object = new MTLayoutPreview('layout_{$this->getFieldsetId()}_preview_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
        }, 100);
    });
</script>
";
        $element->setData('after_element_html', $html);
        return $element;
    }
}