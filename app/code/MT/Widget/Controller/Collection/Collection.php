<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */
namespace MT\Widget\Controller\Collection;

class Collection extends \Magento\Framework\App\Action\Action{

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    protected $_helperData;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MT\Widget\Helper\Data $helperData,
        \Magento\Framework\View\Layout $layout
    ) {
        $this->layout = $layout;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }
    
    public function execute()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) return null;

        $type   = $this->getRequest()->getPost('type');
        $value  = $this->getRequest()->getPost('value');

        if (!$type && !$value) return null;

        $limit      = $this->getRequest()->getPost('limit', 10);
        $carousel   = $this->getRequest()->getPost('carousel', 0);
        $column     = $this->getRequest()->getPost('column', 4);
        $row        = $this->getRequest()->getPost('row', 1);
        $cid        = $this->getRequest()->getPost('cid');
        $template   = $this->getRequest()->getPost('template', 'widget/collection/collection.phtml');

        /* @var $block MT_Widget_Block_Widget_Collection */
        $block = $this->layout->createBlock('MT\Widget\Block\Widget\Collection');
        $params = array();

        if ($cid){
            $params['category_ids'] = explode(',', $cid);
        }

        $block->setTemplate($template);
        $block->setData(array(
            'row'           => $row,
            'column'        => $column,
            'type'          => $type,
            'value'          => $value,
            'category_ids'   => $cid,
            'carousel'      => $carousel,
            'collection'    => $this->_helperData->getProducts($type, $value, $params, $limit)
        ));

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);

        return $resultRaw->setContents($block->toHtml());
    }
}
