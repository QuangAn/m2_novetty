define([
    "jquery",
    "MT_Filter/js/nprogress"
], function($, NProgress){
    ;'use strict';
    var MTFilter = {
        container: null,
        layer: null,
        name: null,
        init: function(name, config){
            this.name = name;
            this.config = config;
            if (this.config.enable){
                if (this.config.bar){
                    NProgress.configure({
                     showSpinner: true
                     });
                }
                $(function(){
                    MTFilter.collect();
                });
            }
        },
        collect: function(){
            this.container = $('.main-product-list').eq(0);
            this.layer = $('#layered-filter-block');
            this.initLinkFilter();
        },
        setConfig: function(obj){
            Object.extend(this.config, obj);
        },
        initLinkFilter: function(){
            if ($('.toolbar-products:first').length){
                $('.toolbar-products:first').find('a').each(function(){
                    $(this).on('click', function(ev){

                        ev.preventDefault();
                        MTFilter.sendRequest($(this).attr('href'));
                    });
                });
            }

            if (this.layer){
                this.layer.find('a').each(function(){
                    $(this).on('click', function(ev){
                        ev.preventDefault();
                        MTFilter.sendRequest($(this).attr('href'));
                    });
                });
            }
        },
        initPriceFilter: function(obj){
            var slider      = $(obj.id),
                handles     = slider.select('.price-slider-handle'),
                minText     = $('layer-price-min'),
                maxText     = $('layer-price-max'),
                range       = $R(obj.min, obj.max),
                URL         = new URI(obj.url);

            minText.update(obj.values[0]);
            maxText.update(obj.values[1]);
            var sliderObj = new Control.Slider(handles, slider, {
                range: range,
                sliderValue: obj.values,
                spans: slider.select('.price-slider-span'),
                restricted: true,
                onSlide: function(values){
                    minText.update(Math.floor(values[0]));
                    maxText.update(Math.ceil(values[1]));
                },
                onChange: function(values){
                    var priceMin = Math.floor(values[0]),
                        priceMax = Math.ceil(values[1]);

                    sliderObj.setDisabled();
                    URL.setQuery('price', priceMin + '-' + priceMax);
                    this.sendRequest(URL.toString(), function(){
                        sliderObj.setEnabled();
                    });
                }.bind(this)
            });
        },
        getParams: function(){
            return {
                isAjax: true,
                form_key: jQuery('input[name="form_key"]').val()
            };
        },
        setAjaxLocation: function(url){

            if (this.getURLParameter(url,'limit') || this.getURLParameter(url,'product_list_order')){
                this.sendRequest(url);
            }else setLocation(url);
        },
        getURLParameter: function(sUrl, sParam){

            var sURLVariables = sUrl.split('&');

            for (var i = 0; i < sURLVariables.length; i++)

            {

                var sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] == sParam)

                {

                    return sParameterName[1];

                }
            }
        },
        sendRequest: function(url, success, error){
            if (this.config.enable){
                if (this.config.bar) NProgress.start();
                jQuery.ajax({
                        method: "POST",
                        url: url,
                        data : this.getParams(),
                        dataType : 'json'}
                ).done(function (response) {
                        if (MTFilter.config.bar) NProgress.done();
                        try{
                            var   main = response.main ? response.main.replace(/setLocation/g, this.name+'.setAjaxLocation') : null,
                                layer = response.layer || null;

                            if (main && MTFilter.container) MTFilter.container.html(main);
                            if (layer && MTFilter.layer) MTFilter.layer.html(layer);
                            //setGridItem($mt);
                            setTimeout(function(){
                                MTFilter.collect();
                            });



                            if (success) success(response);
                        }catch(e){
                            console.log(e.message);
                        }


                    });
            }else{
                setLocation(url);
            }
        }
    }
    window.Filter = MTFilter;
    return MTFilter;
});
