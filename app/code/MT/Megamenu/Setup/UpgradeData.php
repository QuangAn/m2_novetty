<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MT\Megamenu\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Upgrade Data script
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $eavSetupFactory ;

    /**
     * Init
     *
     * @param CategorySetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory  $eavSetupFactory)
    {
        $this->eavSetupFactory  = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        

          // set new resource model paths
            /** @var \Magento\Catalog\Setup\CategorySetup $eavSetup */
            $eavSetup  = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'verticalmenu_is_category', [
            'group'             => 'Main Menu',
            'label'             => 'Display in vertical menu',
            'note'              => "Display category in vertical-menu. This field is applicable only for top-level categories.",
            'type'              => 'varchar',
            'input'             => 'select',
            'source'            => 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Display',
            'visible'           => true,
            'required'          => false,
            'backend'           => '',
            'frontend'          => '',
            'searchable'        => false,
            'filterable'        => false,
            'comparable'        => false,
            'user_defined'      => true,
            'visible_on_front'  => true,
            'wysiwyg_enabled'   => false,
            'is_html_allowed_on_front'  => false,
            'global'            => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $setup->endSetup();
    }
}
