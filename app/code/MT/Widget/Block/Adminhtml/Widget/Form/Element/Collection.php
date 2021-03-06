<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block\Adminhtml\Widget\Form\Element;

use MT\Widget\Model\Widget\Source\Tab\Mode;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Collection extends Template implements RendererInterface{

    protected $_element;

    protected $_modelMode;

    /**
     * @var string
     */
    protected $_template = 'widget/form/element/collection.phtml';


    public function getElement(){
        return $this->_element;
    }

    public function setElement(AbstractElement $element){
        return $this->_element = $element;
    }

    public function render(AbstractElement $element){
        $this->_element = $element;
        return $this->toHtml();
    }

    public function getOptions(){

        $output = array();
        $values = $this->getElement()->getValue();

        if (!is_array($values)) $values = explode(',', $values);
        //$sourceModel = $this->_objectManager->create('MT\Widget\Widget\Source\Tab\Mode');
        $this->_modelMode = new Mode();
        $options = $this->_modelMode->toOptionArray();

        foreach ($values as $value){
            foreach ($options as $option){
                if ($option['value'] == $value){
                    array_push($output, array(
                        'value' => $option['value'],
                        'label' => $option['label'],
                        'selected'  => true
                    ));
                }
            }
        }

        foreach ($options as $option){
            if (!in_array($option['value'], $values)){
                array_push($output, array(
                    'value' => $option['value'],
                    'label' => $option['label'],
                    'selected'  => false
                ));
            }
        }

        return $output;
    }
}
