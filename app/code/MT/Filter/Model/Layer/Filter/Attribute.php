<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MT\Filter\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;

/**
 * Layer attribute filter
 */
class Attribute extends AbstractFilter
{


    /** @var  FilterSetting */
    protected $settingHelper;

    public $attributeValue = null;

    /**
     * @var \Magento\Framework\Filter\StripTags
     */
    private $tagFilter;

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Filter\StripTags $tagFilter
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Filter\StripTags $tagFilter,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->tagFilter = $tagFilter;
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $attributeValue = $request->getParam($this->_requestVar);
        if (empty($attributeValue)) {
            return $this;
        }
        $this->attributeValue = $attributeValue;
        $values = explode('-',$attributeValue);

        $attribute = $this->getAttributeModel();
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();
        foreach($values as $value){
            if(empty($value)) continue;
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $value,true);
            $label = $this->getOptionText($value);
            $this->getLayer()
                ->getState()
                ->addFilter($this->_createItem($label, $value));
        }
        //$this->setItems([]); // set items to disable show filtering
        return $this;
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollectionOrigin = $this->getLayer()
            ->getProductCollection();

        if($this->attributeValue){
            $productCollection = clone $productCollectionOrigin;
            $requestBuilder = clone $productCollection->_memRequestBuilder;
            $productCollection->setRequestData($requestBuilder->removeData($attribute->getAttributeCode()));
            $productCollection->clear();
            $productCollection->loadWithFilter();
            $collection = $productCollection;
        }else{
            $collection = $productCollectionOrigin;
        }
        //$requestBuilder = $productCollection->_memRequestBuilder->setOnlyRootCategory($this->getLayer()->getCurrentCategory()->getId());
        //$productCollection->setRequestData($requestBuilder);
        $optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());

        $productSize = $collection->getSize();

        $options = $attribute->getFrontend()
            ->getSelectOptions();
        foreach ($options as $option) {
            if (empty($option['value'])) {
                continue;
            }
            // Check filter type
            /*            if (empty($optionsFacetedData[$option['value']]['count'])
                            || ($this->getAttributeIsFilterable($attribute) == static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS
                                && !$this->isOptionReducesResults($optionsFacetedData[$option['value']]['count'], $productSize))
                        ) {
                            continue;
                        }*/
            if(isset($optionsFacetedData[$option['value']])){
                $this->itemDataBuilder->addItemData(
                    $this->tagFilter->filter($option['label']),
                    $option['value'],
                    $optionsFacetedData[$option['value']]['count']
                );
            }

        }

        return $this->itemDataBuilder->build();
    }
}
