<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block\Adminhtml\Widget\Form\Element;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Video extends \Magento\Backend\Block\Widget implements RendererInterface{

    protected $_element;

    protected $_template = 'widget/form/element/video.phtml';

    public function getElement(){
        return $this->_element;
    }

    public function setElement(AbstractElement $element){
        return $this->_element = $element;
    }

    public function render(AbstractElement $element){
        $this->setElement($element);
        return $this->toHtml();
    }

    protected function _beforeToHtml(){
        $getBtn = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'label'     => __('Get'),
            'title'     => __('Click to preview video'),
            'class'     => 'add',
            'type'      => 'button',
            'id'        => 'videoSearchBtn',
            'onclick'   => "return window.{$this->getElement()->getHtmlId()}.search()"
        ]);
        $this->setChild('getBtn', $getBtn);
        $delBtn = $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Button')->setData([
            'label'     => __('x'),
            'title'     => __('Click to remove url'),
            'type'      => 'button',
            'id'        => 'videoRemoveBtn',
            'onclick'   => "return window.{$this->getElement()->getHtmlId()}.remove()"
        ]);
        $this->setChild('delBtn', $delBtn);

        return parent::_beforeToHtml();
    }
}