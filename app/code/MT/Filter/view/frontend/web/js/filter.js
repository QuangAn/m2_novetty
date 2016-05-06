define([
    "MT_Filter/js/nprogress",
    "jquery",
    "jquery/ui"
], function(NProgress, $){
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
            this.container = $('.main').eq(0);
            this.layer = $('#layered-filter-block');
            this.initLinkFilter();
            this.initPriceFilter();
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
                this.layer.find('input[type="checkbox"]').each(function(){
                    var  parent = $(this).parents('.item');
                    $(this).on('click', function(ev){
                        MTFilter.sendRequest(parent.find('a').attr('href'));
                    });
                });

                this.layer.find('a').each(function(){
                    var parent = $(this).parents('.item');
                    if($(this).attr('href') == '#') return ;
                    $(this).on('click', function(ev){
                        parent.find('input[type="checkbox"]').prop('checked', !parent.find('input[type="checkbox"]').prop('checked'));
                        if($(this).parents('.swatch-attribute-options').length && !$(this).hasClass('selected')) $(this).addClass('selected')
                        else $(this).removeClass('selected');
                        ev.preventDefault();
                        MTFilter.sendRequest($(this).attr('href'));
                    });
                });

            }
        },
        initPriceFilter: function(){
            var option = $("#mt_layer_filter").data();

            $("#mt_layer_filter_display").html( MTFilter.renderLabel(option.template, option.from) + " - " + MTFilter.renderLabel(option.template, option.to) );
            $('#mt_layer_filter').slider({
                range: true,
                min: option.min,
                max: option.max,
                values: [ option.from, (option.to)],
                slide: function( event, ui ) {
                    $("#mt_layer_filter_display").html( MTFilter.renderLabel(option.template, ui.values[ 0 ]) + " - " + MTFilter.renderLabel(option.template, ui.values[ 1 ]) );
                },
                change: function( event, ui ) {
                    var href = option.url.replace('slider_from', ui.values[ 0 ]).replace('slider_to', ui.values[1]);
                    MTFilter.sendRequest(href);
                }
            });
        },
        renderLabel:function(template, value) {
            return template.replace('{amount}', value);
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
