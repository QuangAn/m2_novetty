define([
    "jquery",
    "jquery/ui"
], function ($) {
    'use strict';
    /* set Height for element */
    $.widget('mage.equalHeight', {
        options: {
            target: '.product-item-info'
        },
        _create: function(){
            var $_e = this.element;
            if($_e.length){
                var $_maxHeight= 0;
                var $listItems = $_e.find('.product-item-info');
                var lenLi = $listItems.length;
                $listItems.css('height', '');
                if(lenLi>1){
                    for(var j=0;j<lenLi;j++){
                        $_maxHeight = Math.max($_maxHeight, $listItems.eq(j).outerHeight());
                    }
                }
				var productaction = $_e.find('.product-item-actions').eq(0);
                var productActionHeight = productaction.outerHeight();
                
                 
                
				$listItems.css('padding-bottom', productActionHeight);
                $listItems.css('height', ($_maxHeight + productActionHeight ) + 'px');
            }
        }


    });
	
    //return $.mage.equalHeight;
});


