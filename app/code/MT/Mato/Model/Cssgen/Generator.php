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
namespace MT\Mato\Model\Cssgen;

use Magento\Framework\Filesystem\DriverInterface;
use MT\Mato\Model\lessc;

class Generator extends \Magento\Framework\Model\AbstractModel{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Catalog data
     *
     * @var Data
     */
    protected $_helperCssgen = null;

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    protected $_file;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \MT\Mato\Helper\Cssgen $helperCssgen,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\View\Layout $layout,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ){
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_storeManager = $storeManager;
        $this->_helperCssgen = $helperCssgen;
        $this->layout = $layout;
        $this->messageManager = $messageManager;
        $this->_file = $file;
    }

    public function generateCss($design, $websiteCode, $storeCode){
        if ($websiteCode){
            if ($storeCode) {
                $this->_generateStoreCss($design, $storeCode);
            } else {
                $this->_generateWebsiteCss($design, $websiteCode);
            }
        }else{
            $website = $this->_storeManager->getWebsites(false, true);
            foreach ($website as $value => $name) {
                $this->_generateWebsiteCss($design, $value);
            }
        }
    }
    protected function _generateWebsiteCss($design, $websiteCode) {
        $website = $this->_storeManager->getWebsite($websiteCode);
        foreach ($website->getStoreCodes() as $site){
            $this->_generateStoreCss($design, $site);
        }
    }
    protected function _generateStoreCss($design, $storeCode){
        if (!$this->_storeManager->getStore($storeCode)->getIsActive()) return;
        $prefix = '_' . $storeCode;
        if($design == 'layout'){
            $filename = $design . $prefix . '.css';
        }else{
            $filename = $design . $prefix . '.less';
        }
        $filedefault = $this->_helperCssgen->getGeneratedCssDir() . $filename;
        $path = 'MT_Mato::magenthemes/css/' . $design . '.phtml';
        try{

            $block = $this->layout->createBlock('\Magento\Framework\View\Element\Template')->setData(array('area' => 'frontend', 'cssgen_store' => $storeCode))->setTemplate($path)->toHtml();
            if (empty($block)) {
                throw new \Exception( __("Template file is empty or doesn't exist: %s", $path) );
            }
            if(!$this->_file->isExists($this->_helperCssgen->getGeneratedCssDir())){
                $this->_file->createDirectory($this->_helperCssgen->getGeneratedCssDir(),DriverInterface::WRITEABLE_DIRECTORY_MODE);
            }
            $resourceFile = $this->_file->fileOpen($filedefault, 'w+');
            $this->_file->fileLock($resourceFile);
            $this->_file->fileWrite($resourceFile, $block);
            $this->_file->fileUnlock($resourceFile);
            $this->_file->fileClose($resourceFile);

            //Compile file from less to css
            if($design == 'design'){

                $cssFile  = substr($filedefault, 0, -5) . '.css';
                try {
                    $newCache = lessc::cexecute($filedefault);
                } catch (\Exception $e) {
                    throw $e;
                }

                if (!is_string($result = $this->_file->isWritable($cssFile))) {
                    file_put_contents($cssFile, $newCache['compiled']);
                } else {
                    throw new \Exception($result);
                }
            }

        }catch (\Exception $gener){
            $this->messageManager->addError(__('Failed generating CSS file: '.$filename.' in ' . $this->_helperCssgen->getGeneratedCssDir() ). '<br/>Message: ' . $gener->getMessage());
            $this->_logger->critical($gener);
        }
    }
}