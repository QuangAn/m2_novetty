<?php

namespace MT\Filter\Block\Navigation;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;

class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
{

    protected $_helper;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \MT\Filter\Helper\Data $helper, array $data = [])
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function render(FilterInterface $filter)
    {
        if( $filter instanceof \Magento\CatalogSearch\Model\Layer\Filter\Category ) $filterCode = '';
        else
            $filterCode = $filter->getAttributeModel()->getAttributeCode();
        if($filterCode == 'price' && $this->_helper->getConfig('mtfilter/catalog/price')) {
            $this->setTemplate("layer/price.phtml");
        }
        else {
            $this->setTemplate("layer/filter.phtml");
        }

        return parent::render($filter);
    }

    public function checkedFilter($filterItem)
    {
        $data = $this->getRequest()->getParam($filterItem->getFilter()->getRequestVar());
        if (!empty($data)) {
            $ids = explode('-', $data);
            if (in_array($filterItem->getValue(), $ids)) {
                return 1;
            }
        }
        return 0;
    }

}
