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
namespace MT\Mato\Block\Adminhtml\Patterns\Upload;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Patterns extends Field
{
    protected function _getElementHtml(AbstractElement $element){
       	$html = parent::_getElementHtml($element);
		$directry = $this->getMediaDirectory()->getAbsolutePath().'wysiwyg/mato/images/patterns/';
		$urlparth = $this->getViewFileUrl('MT_Mato::css/');
		$baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$images = array();

		if (is_dir($directry)) {
			if ($dh = opendir($directry)) { 
				while (($file = readdir($dh)) !== false) {
					if(is_file($directry.'/'.$file)){
						$filetype = substr($file, -3, 3);
						switch ($filetype)
						{ 
							case 'jpg':
							case 'png':
							case 'gif':  
								$images[] = $file; 
								break; 
						}
					} 
				} 
			}
		}

        $html .='<link href="'.$urlparth.'/style.css'.'" type="text/css" rel="stylesheet">';
		$html .='<div class="listpattern '.$element->getHtmlId().'_pattern">';
			$html .='<span class="item">
						<span class="ptnone">None</span>
						<input type="radio" name="'.$element->getHtmlId().'_pattern" value="none" style="margin: 8px; 0 4px 0" class="valpt"/>
					 </span>';
			if($images){
				foreach ($images as $img){
			$html .='<span class="item">
						<img src="'.$baseUrl."wysiwyg/mato/images/patterns/".$img.'" />
						<input type="radio" name="'.$element->getHtmlId().'_pattern" value="'.$img.'" class="valpt"/>
					 </span>';
				}
			}
		$html .='</div>';
		$html .= '<script>
					require([
						"jquery",
						"jquery"
                    ],function(){
					jQuery(window).load(function(){
						jQuery(".'.$element->getHtmlId().'_pattern input[type=radio]").click(function(){
							jQuery("#'.$element->getHtmlId().'").val(jQuery(this).val())
						});
						pattern'.$element->getHtmlId().'Active();
					});
					function pattern'.$element->getHtmlId().'Active(){
						var ptnbody =jQuery("#'.$element->getHtmlId().'").val();
						jQuery(".'.$element->getHtmlId().'_pattern input[type=radio]").each(function(i,rad){
							if(rad.value==ptnbody){
								jQuery(rad).attr("checked", true);
							}
						});
					}
				});
			</script>
			';
        return $html;
    }
}
?>