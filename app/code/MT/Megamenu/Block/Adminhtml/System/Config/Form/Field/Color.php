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

namespace MT\Megamenu\Block\Adminhtml\System\Config\Form\Field;
class Color extends \Magento\Config\Block\System\Config\Form
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){ 
       	$html = parent::_getElementHtml($element);  
       	$html .= ' 
       		<script type="text/javascript">
				jQuery(function($){
					$("#'. $element->getHtmlId() .'").attr("data-hex", true).width("250px").mColorPicker();
				});
			</script>
       	';
        return $html;
    }
}
?>