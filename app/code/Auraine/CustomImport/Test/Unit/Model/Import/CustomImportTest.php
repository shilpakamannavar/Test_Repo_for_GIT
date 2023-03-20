<?php

namespace Auraine\CustomImport\Test\Unit\Model\Import;

use Auraine\CustomImport\Model\Import\CustomImport;
use Auraine\CustomImport\Model\Import\CustomImport\RowValidatorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\ResourceConnection;

class CustomImportTest extends TestCase
{
    /**
     * @var CustomImport|MockObject
     */
    private $customImport;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProcessingErrorAggregatorInterface|MockObject
     */
    private $errorAggregator;

    protected function setUp(): void
    {
            parent::setUp();
            // Create a mock object of the dependencies
            $jsonHelper = $this->createMock(\Magento\Framework\Json\Helper\Data::class);
            $importExportData = $this->createMock(\Magento\ImportExport\Helper\Data::class);
            $importData = $this->createMock(\Magento\ImportExport\Model\ResourceModel\Import\Data::class);
            $resource = $this->createMock(\Magento\Framework\App\ResourceConnection::class);
            $resourceHelper = $this->createMock(\Magento\ImportExport\Model\ResourceModel\Helper::class);
            $errorAggregator = $this->createMock(\Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface::class);
            $groupFactory = $this->createMock(\Magento\Customer\Model\GroupFactory::class);

        // Create an instance of the class under test
        $this->customImport = new CustomImport(
            $jsonHelper,
            $importExportData,
            $importData,
            $resource,
            $resourceHelper,
            $errorAggregator,
            $groupFactory
        );
    }

    public function testGetEntityTypeCode()
    {
        $this->assertEquals('pincode', $this->customImport->getEntityTypeCode());
    }
    public function testGetValidColumnNames()
    {
        $this->assertEquals(
            [
                CustomImport::CODE,
                CustomImport::CITY,
                CustomImport::STATE,
                CustomImport::COUNTRY,
                CustomImport::STATUS,
            ],
            $this->customImport->getValidColumnNames()
        );
    }
}

