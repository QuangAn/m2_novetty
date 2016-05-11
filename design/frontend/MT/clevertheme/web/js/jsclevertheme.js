 jQuery(function () {
    jQuery.material.init(); 
    jQuery.material.ripples(".btn-raised");
    jQuery.material.ripples(".owl-nav div");
    jQuery.material.ripples(".actions-primary"); 
    jQuery.material.ripples(".mt-actions-secondary a");
    jQuery.material.ripples(".actions a.btn-raised");
     
     jQuery("#switcher-language").hover(
          function () {
            jQuery(this).find('.mage-dropdown-dialog').show();
            jQuery(this).find('#switcher-language-trigger, .switcher-options').addClass("active");
          }, 
          function () {
            jQuery(this).find('.mage-dropdown-dialog').hide();
            jQuery(this).find('#switcher-language-trigger, .switcher-options').removeClass("active");
          }
      );
      
 
     
      
     // equal height function
     (function($){
        	$.fn.equalboxes = function(){
        		var maxheight = 0,
        			rowheight = 0,
        			rowstart = 0,
        			height = 0,
        			boxes = [],
        			top = 0,
        			jel = null;
        
        		//all equalheight (item will not align like a mess)
        		this.each(function(){
        			jel = $(this);
        			height = jel.css({'height': '', 'min-height': ''}).removeClass('eq-first').height();
        
        			if(height > maxheight){
        				maxheight = height;
        			}
        
        			jel.data('orgHeight', height);
        
        		}).css('min-height', maxheight);
        
        		//per row equal-height
        		this.each(function() {
        			jel = $(this);
        			height = jel.data('orgHeight');
        			top = jel.position().top;
        
        			if (rowstart != top) {
        				boxes.length && $(boxes).css('min-height', rowheight + 1).eq(0).addClass('eq-first');
        
        				// set the variables for the new row
        				boxes.length = 0;
        				rowstart = jel.position().top;
        				rowheight = height;
        				boxes.push(this);
        
        			} else {
        				boxes.push(this);
        				if(height > rowheight){
        					rowheight = height;
        				}
        			}
        		});
        
        		boxes.length && $(boxes).css('min-height', rowheight + 1).eq(0).addClass('eq-first');
        
        		return this;
        	};
        
        	$.fn.eqboxs = function(){
        		
        		//should be more than two elements
        		if(this.length < 2){
        			return this;
        		}
        
        		var elms = this,
        			rzid = null,
        			resize = function () {
        				elms.equalboxes();
        			};
        
        		$(window).load(function(){
        			//trigger one
        			elms.equalboxes();
        
        			clearTimeout(rzid);
        			rzid = setTimeout(resize, 2000); //just in case something new loaded
        		}).on('resize.eqb', function(){
        			clearTimeout(rzid);
        			rzid = setTimeout(resize, 200);
        		});
        
        		//trigger one
        		elms.equalboxes();
        
        		return this;
        	};
        
        })(jQuery);
        
        jQuery('.equal-height').children().eqboxs();
  }); 