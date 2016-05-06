define([
    "jquery",
    "jquery/ui",
    "MT_Widget/js/owl-carousel/owl.carousel"
], function ($) {
    'use strict';
    $.widget('mage.mtFrontend', {
        options: {
        },

        _create: function(){
            this.initCarousel();
			console.log(this.options);
        },

        initCarousel: function () {
            this.initCarouselElement(this.options.carousel);
        },

        initCarouselElement: function (config) {
            var $listItems = this.element.find('.owl-carousel');
            var lenLi = $listItems.length;
			console.log(lenLi);
			if (!jQuery.fn.owlCarousel) console.log("XXXFFFxxxxxxxxxx");
			jQuery($listItems[0]).owlCarousel(config);
			console.log($listItems[0]);
            if(lenLi>=1){
                for(var j=0;j<lenLi;j++){
                    //jQuery($listItems[j]).owlCarousel(config);
                }
            }
        }
    });
});