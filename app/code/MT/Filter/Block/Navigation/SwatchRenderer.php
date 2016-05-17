<?php

namespace MT\Filter\Block\Navigation;



use Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;

class SwatchRenderer extends \Magento\Swatches\Block\LayeredNavigation\RenderLayered
{
    protected $_template = "layer/renderer.phtml";
    protected $urlBuilderHelper;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, Attribute $eavAttribute,
        AttributeFactory $layerAttribute,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Swatches\Helper\Media $mediaHelper,
        \MT\Filter\Helper\UrlBuilder $urlBuilderHelper,
        array $data = []
    ) {
        parent::__construct(
            $context, $eavAttribute, $layerAttribute, $swatchHelper,
            $mediaHelper, $data
        );
        $this->urlBuilderHelper = $urlBuilderHelper;
    }

    /**
     * @param FilterItem $filterItem
     * @param Option $swatchOption
     * @return array
     */
    protected function getOptionViewData(FilterItem $filterItem, Option $swatchOption)
    {
        $customStyle = '';
        $linkToOption = $this->buildUrl($this->eavAttribute->getAttributeCode(), $filterItem->getValue());
        if ($this->isOptionDisabled($filterItem)) {
            $customStyle = 'disabled';
            $linkToOption = 'javascript:void();';
        }

        return [
            'label' => $swatchOption->getLabel(),
            'link' => $linkToOption,
            'count' => $filterItem->getCount(),
            'custom_style' => $customStyle
        ];
    }

    /**
     * @param string $attributeCode
     * @param int $optionId
     * @return string
     */
    public function buildUrl($attributeCode, $optionId)
    {
        return $this->urlBuilderHelper->buildUrl($this->filter, $optionId);
    }

    public function checkedFilter($filterItem)
    {
        $data = $this->getRequest()->getParam($this->filter->getRequestVar());
        if (!empty($data)) {
            $ids = explode('-', $data);
            if (in_array($filterItem, $ids)) {
                return 1;
            }
        }
        return 0;
    }
}
