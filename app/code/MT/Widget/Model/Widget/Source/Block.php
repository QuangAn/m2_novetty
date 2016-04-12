<?php
/**
 * @category    MT
 * @package     MT_Widget
 * @copyright   Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */
namespace MT\Widget\Model\Widget\Source;

use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;

class Block implements \Magento\Framework\Option\ArrayInterface{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(){

        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionArray();
        }
        return $this->options;
    }
}