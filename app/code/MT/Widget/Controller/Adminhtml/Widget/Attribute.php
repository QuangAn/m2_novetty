<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Controller\Adminhtml\Widget;

use Magento\Backend\App\Action;
class Attribute extends \Magento\Backend\App\Action{

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ){
        $this->_jsonEncoder = $jsonEncoder;
        parent::__construct($context);
    }


    public function execute(){
        if ($this->getRequest()->isPost()){
            $values = explode(',', $this->getRequest()->getParam('value'));
            if (count($values) == 2){
                list($attributeId, $attributeCode) = $values;
                $optionCollection = $this->_objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')
                    ->setAttributeFilter($attributeId)
                    ->setStoreFilter()
                    ->load();
                $options = array();
                foreach ($optionCollection as $option){
                    $options[] = array(
                        'id' => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage()
                    );
                }
                $this->getResponse()->setBody($this->_jsonEncoder->encode($options));
            }
        }
    }
}