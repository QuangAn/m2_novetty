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
namespace MT\Mato\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Cssgen extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Path and directory of the automatically generated CSS
     *
     * @var string
     */
    protected $_generatedCssFolder;
    protected $_generatedCssDir;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Filesystem
     */
    protected $_fileSystem;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        $this->_fileSystem = $fileSystem;

        //Create paths
        $this->_generatedCssFolder = 'css/_config/';
        $this->_generatedCssDir = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('magenthemes/web') . '/' . $this->_generatedCssFolder;

        parent::__construct($context);
    }

    /**
     * Get automatically generated CSS directory
     *
     * @return string
     */
    public function getGeneratedCssDir()
    {
        return $this->_generatedCssDir;
    }

    /**
     * Get file path: CSS design
     *
     * @return string
     */
    public function getDesignFile()
    {
        $meidaUrl = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $meidaUrl . 'magenthemes/web/css/_config/design_' . $this->_storeManager->getStore()->getCode() . '.css';
    }

    /**
     * Get file path: CSS layout
     *
     * @return string
     */
    public function getLayoutFile()
    {
        $meidaUrl = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $meidaUrl . 'magenthemes/web/css/_config/layout_' . $this->_storeManager->getStore()->getCode() . '.css';
    }
}
