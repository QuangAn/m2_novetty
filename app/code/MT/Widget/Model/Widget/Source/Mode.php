<?php
/**
 * @category    MT
 * @package     MT_Widget
 * Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author ZooExtension.com
 * @email       magento@cleversoft.co
 */

namespace MT\Widget\Model\Widget\Source;

class Mode implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        $types = [
            ['value' => 'latest', 'label' => __('Latest Products')],
            ['value' => 'new', 'label' => __('New Products')],
            ['value' => 'bestsell', 'label' => __('Best Sell Products')],
            ['value' => 'mostviewed', 'label' => __('Most Viewed Products')],
            ['value' => 'id', 'label' => __('Specified Products')],
            ['value' => 'random', 'label' => __('Random Products')],
            ['value' => 'related', 'label' => __('Related Products')],
            ['value' => 'upsell', 'label' => __('Up-sell Products')],
            ['value' => 'crosssell', 'label' => __('Cross-sell Products')],
            ['value' => 'discount', 'label' => __('Discount Products')],
            ['value' => 'rating', 'label' => __('Top Rated Products')],
            ['value' => 'recentviewed', 'label' => __('Recent Viewed Products')],
        ];

        return $types;
    }
}
