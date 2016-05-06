<?php

namespace MT\Filter\Model\Layer\Filter;


class Price extends \Magento\CatalogSearch\Model\Layer\Filter\Price
{
    protected $_fromTo;

    protected $minMaxPrice;

    protected $currencySymbol;

    protected $helper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \MT\Filter\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->currencySymbol = $priceCurrency->getCurrencySymbol();
        parent::__construct(
            $filterItemFactory, $storeManager, $layer, $itemDataBuilder,
            $resource, $customerSession, $priceAlgorithm, $priceCurrency,
            $algorithmFactory, $dataProviderFactory, $data
        );
    }

    protected function _initItems()
    {
        if(!$this->helper->getConfig('mtfilter/catalog/price')) {
            return parent::_initItems();
        }
        $this->_items = [
            [
                'from'          => $this->getCurrentFrom(),
                'to'            => $this->getCurrentTo(),
                'min'           => $this->getMinPrice(),
                'max'           => $this->getMaxPrice(),
                'requestVar'    => $this->getRequestVar(),
                'step'          => 4,
                'template'      => $this->currencySymbol . '{amount}',
            ]
        ];
        return $this;
    }


    public function getMinPrice()
    {
        if(is_null($this->minMaxPrice)) {
            $this->initMinMaxPrice();
        }
        return $this->minMaxPrice['min'];
    }

    public function getMaxPrice()
    {
        if(is_null($this->minMaxPrice)) {
            $this->initMinMaxPrice();
        }
        return $this->minMaxPrice['max'];
    }

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $apply = parent::apply($request);
        $filter = $request->getParam($this->getRequestVar());
        if(!empty($filter) && !is_array($filter)) {
            list($from, $to) = explode('-', current(explode(',', $filter)));
            $this->_fromTo['from'] = $from;
            $this->_fromTo['to'] = $to;
        }

        return $apply;
    }


    public function getCurrentFrom()
    {
        return empty($this->_fromTo['from']) ? $this->getMinPrice() : $this->_fromTo['from'];
    }

    public function getCurrentTo()
    {
        return empty($this->_fromTo['to']) ? $this->getMaxPrice() : $this->_fromTo['to'];
    }


    protected function initMinMaxPrice()
    {
        $collection = clone $this->getLayer()->getProductCollection();
        $this->minMaxPrice = [
            'min' => $collection->getMinPrice(),
            'max' => $collection->getMaxPrice(),
        ];

    }

}
