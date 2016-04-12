<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MT\Megamenu\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Upgrade Data script
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
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
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        

        // set new resource model paths
        /** @var \Magento\Catalog\Setup\CategorySetup $eavSetup */
        $eavSetup  = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_block_right', [
            'group'				=> 'Main Menu',
            'label'				=> 'Block Right',
            'note'				=> "This field is applicable only for top-level categories.",
            'type'				=> 'text',
            'input'				=> 'textarea',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> true,
            'is_html_allowed_on_front'	=> true,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_proportions_right', [
            'group'				=> 'Main Menu',
            'label'				=> 'Proportions: Block Right',
            'note'				=> "Proportions block Block Right. This field is applicable only for top-level categories.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Proportions',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_block_left', [
            'group'				=> 'Main Menu',
            'label'				=> 'Block Left',
            'note'				=> "This field is applicable only for top-level categories.",
            'type'				=> 'text',
            'input'				=> 'textarea',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> true,
            'is_html_allowed_on_front'	=> true,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_proportions_left', [
            'group'				=> 'Main Menu',
            'label'				=> 'Proportions: Block Left',
            'note'				=> "Proportions block Block Left. This field is applicable only for top-level categories.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Proportions',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_columns', [
            'group'				=> 'Main Menu',
            'label'				=> 'Categories Columns',
            'note'				=> "This field is applicable only for top-level categories.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Proportions',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_groups', [
            'group'				=> 'Main Menu',
            'label'				=> 'Category Menu Type',
            'note'				=> "Show subcategories by. This field is applicable only for top-level categories.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Types',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_block_top', [
            'group'				=> 'Main Menu',
            'label'				=> 'Block Top',
            'type'				=> 'text',
            'input'				=> 'textarea',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> true,
            'is_html_allowed_on_front'	=> true,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_block_bottom', [
            'group'				=> 'Main Menu',
            'label'				=> 'Block Bottom',
            'type'				=> 'text',
            'input'				=> 'textarea',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> true,
            'is_html_allowed_on_front'	=> true,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_cat_label', [
            'group'				=> 'Main Menu',
            'label'				=> 'Category Label',
            'note'				=> "Labels have to be defined in menu settings",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Categorylabel',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_is_category', [
            'group'				=> 'Main Menu',
            'label'				=> 'Display in menu',
            'note'				=> "Display category in menu. This field is applicable only for top-level categories.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Block\Display',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_is_alignment', [
            'group'				=> 'Main Menu',
            'label'				=> 'Alignment',
            'note'				=> "Align submenu.",
            'type'				=> 'varchar',
            'input'				=> 'select',
            'source'			=> 'MT\Megamenu\Model\System\Config\Source\Category\Attribute\Source\Align\Align',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_is_width', [
            'group'				=> 'Main Menu',
            'label'				=> 'Width',
            'note'				=> "Submenu width.",
            'type'				=> 'text',
            'input'				=> 'text',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> true,
            'is_html_allowed_on_front'	=> true,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'mtmenu_is_icon', [
            'group'				=> 'Main Menu',
            'label'				=> 'Icon Font',
            'note'				=> "Add icon font to level top menu.",
            'type'				=> 'text',
            'input'				=> 'text',
            'visible'			=> true,
            'required'			=> false,
            'backend'			=> '',
            'frontend'			=> '',
            'searchable'		=> false,
            'filterable'		=> false,
            'comparable'		=> false,
            'user_defined'		=> true,
            'visible_on_front'	=> true,
            'wysiwyg_enabled'	=> false,
            'is_html_allowed_on_front'	=> false,
            'global'			=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);

        $setup->endSetup();
    }
}
