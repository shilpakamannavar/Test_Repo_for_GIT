<?php

namespace Auraine\RegionList\Test\Unit\Setup\Patch\Data;

use Auraine\RegionList\Setup\Patch\Data\AddData;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Directory\Model\RegionFactory;
use PHPUnit\Framework\TestCase;

class AddDataTest extends TestCase
{
    protected $moduleDataSetup;
    protected $regionFactory;
    protected $dataPatch;
    protected $connection;

    protected function setUp(): void
    {
        $this->moduleDataSetup = $this->createMock(ModuleDataSetupInterface::class);
        $this->regionFactory = $this->getMockBuilder(RegionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->connection = $this->createMock(Mysql::class);

        $this->dataPatch = new AddData(
            $this->moduleDataSetup,
            $this->regionFactory
        );
    }

    public function testApply()
    {
        $newRegions = [
            'LA' => 'Ladakh'
        ];

        $this->moduleDataSetup
            ->expects($this->exactly(count($newRegions) * 2 + 1))
            ->method('getConnection')
            ->willReturn($this->connection);

        foreach ($newRegions as $code => $name) {
            $this->connection
                ->expects($this->at(0))
                ->method('insert')
                ->with(
                    $this->equalTo($this->moduleDataSetup->getTable('directory_country_region')),
                    $this->equalTo(['country_id' => 'IN', 'code' => $code, 'default_name' => $name])
                );

            $this->connection
                ->expects($this->at(1))
                ->method('lastInsertId')
                ->willReturn(1);

            $this->connection
                ->expects($this->at(2))
                ->method('insert')
                ->with(
                    $this->equalTo($this->moduleDataSetup->getTable('directory_country_region_name')),
                    $this->equalTo(['locale' => 'en_US', 'region_id' => 1, 'name' => $name])
                );
        }

        $this->dataPatch->apply();
    }

    public function testGetDependencies()
    {
        $dependencies = $this->dataPatch->getDependencies();

        // Add your assertions here
        $this->assertEmpty($dependencies);
    }

    public function testGetAliases()
    {
        $aliases = $this->dataPatch->getAliases();

        // Add your assertions here
        $this->assertEmpty($aliases);
    }
}
