<?php

namespace Genesisoft\Base\Setup;

use Magento\Cms\Block\Block;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use \Magento\Cms\Model\BlockFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param BlockFactory $blockFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory, BlockFactory $blockFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->blockFactory = $blockFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->createImageLink($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->updateConfigs($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->createBlockShippingInformation($setup);
        }
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    private function createImageLink(ModuleDataSetupInterface $setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'category_image_link',
            [
                'type' => 'varchar',
                'label' => 'Category Image Link ',
                'input' => 'text',
                'sort_order' => 6,
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'content',
                'backend' => ''
            ]
        );
    }

    public function updateConfigs(ModuleDataSetupInterface $setup)
    {
        $values = [
            'shipping/origin/country_id' => 'BR',
            'general/locale/code' => 'pt_BR',
            'general/locale/timezone' => 'America/Sao_Paulo',
            'general/locale/weight_unit' => 'kgs',
            'general/region/display_all' => '1',
            'general/region/state_required' => 'AU,BR,CA,HR,ES,US,EE,LV,LT,MX,RO,CH,IN',
            'general/country/default' => 'BR',
            'general/store_information/country_id' => 'BR',
            'currency/options/base' => 'BRL',
            'currency/options/default' => 'BRL',
            'currency/options/allow' => 'BRL',
            'customer/address/street_lines' => '4',
            'checkout/options/guest_checkout' => '0',
            'admin/security/session_lifetime' => '36000',
        ];

        $paths = [
            'shipping/origin/country_id',
            'general/locale/code',
            'general/locale/timezone',
            'general/locale/weight_unit',
            'general/region/display_all',
            'general/region/state_required',
            'general/country/default',
            'general/store_information/country_id',
            'currency/options/base',
            'currency/options/default',
            'currency/options/allow',
            'customer/address/street_lines',
            'checkout/options/guest_checkout',
            'admin/security/session_lifetime',
        ];

        $configTable = $setup->getTable('core_config_data');
        $connection = $setup->getConnection();
        $select = $connection->select()->from($configTable, 'path')
            ->where('path IN (?)', $paths);

        $configs = $setup->getConnection()->fetchAll($select);

        foreach ($configs as $config) {
            if (array_search($config['path'], $paths) !== false) {
                unset($values[$config['path']]);
            }
        }

        foreach ($values as $key => $value) {
            $row = [
                'scope' => 'default',
                'scope_id' => '0',
                'path' => $key,
                'value' => $value
            ];
            $setup->getConnection()->insert(
                $setup->getTable('core_config_data'),
                $row
            );
        }
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    private function createBlockShippingInformation(ModuleDataSetupInterface $setup)
    {
        $block = [
            'title' => 'Bloco para informações de entrega',
            'identifier' => 'shipping_information',
            'content' => "<h1>Escreva aqui o detalhamento dos métodos de entrega.......</h1>",
            'stores' => [0],
            'is_active' => 1,
            'sort_order' => 0
        ];
        $this->blockFactory->create()->setData($block)->save();
    }
}
