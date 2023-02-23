<?php
namespace Auraine\RegionList\Setup\Patch\Data;

use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;
      /**
       * @param ModuleDataSetupInterface $moduleDataSetup
       * @param RegionFactory $regionFactory
       */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RegionFactory $regionFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->regionFactory = $regionFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $newRegions = [
            'LA' => 'Ladakh'
        ];

        foreach ($newRegions as $code => $name) {

            $binds = ['country_id'   => 'IN', 'code' => $code, 'default_name' => $name];
            $this->moduleDataSetup->getConnection()->insert($this->moduleDataSetup->
                getTable('directory_country_region'), $binds
            );
            $regionId = $this->moduleDataSetup->getConnection()->
                lastInsertId($this->moduleDataSetup->getTable('directory_country_region'));

            $binds = ['locale'=> 'en_US', 'region_id' => $regionId, 'name'=> $name];
            $this->moduleDataSetup->getConnection()->insert($this->moduleDataSetup->
                getTable('directory_country_region_name'), $binds
            );
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
