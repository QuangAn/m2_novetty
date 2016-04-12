<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Block\Widget;

class Collection extends \Magento\Catalog\Block\Product\AbstractProduct{

    /**
     * Action name for ajax request
     */
    const MEDIA_CALLBACK_ACTION = 'swatches/ajax/media';

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

      /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->urlHelper = $urlHelper;
    }

    /**
     * @return string
     */
    public function getColorSwatchDetailsHtml($product)
    {
        $block = $this->getLayout()->createBlock('MT\Mato\Block\Product\Renderer\Configurable');
        $block->setProduct($product);

        //$block->setTemplate('Magento_Swatches::product/listing/renderer.phtml');

        return $block->toHtml();
    }

    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);

        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}