<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category Mato
 * @package Mato Framework
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2008-2015 ZooExtension.com. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       ZooExtension.com
 * @email        magento@cleversoft.co
 * ------------------------------------------------------------------------------
 *
 */
namespace MT\Mato\Block\Adminhtml\System\Config\Form\Field;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Color extends Field
{
	protected function _getElementHtml(AbstractElement $element){
		$colorpicker = $this->getViewFileUrl('MT_Mato::js/mcolorpicker/');
		$html = parent::_getElementHtml($element);
		$html .= '
       		<script>
				require([
						"MT_Mato/js/mcolorpicker/mcolorpicker"
                    ],function(){
                    	jQuery(document).ready(function(){
							jQuery("#'. $element->getHtmlId() .'").attr("data-hex", true).width("250px").mColorPicker({imageFolder :"'.$colorpicker .'/images/"});
                    	});

                    });
			</script>
       	';
        return $html;
    }

}
?>