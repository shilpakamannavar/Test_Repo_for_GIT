<?php

namespace Auraine\CustomImport\Test\Unit\Model\Source\Import\Behavior;

use Auraine\CustomImport\Model\Source\Import\Behavior\Basic;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\ImportExport\Model\Import;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    /**
     * @var Basic
     */
    private $model;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(Basic::class);
    }

    public function testGetCode()
    {
        $this->assertSame('pincode', $this->model->getCode());
    }
    public function testToArray()
    {
        $this->assertIsArray($this->model->toArray());
    }
   
    public function testToArrayReturnsArrayWithNonEmptyAppendKeyIfImportIsSet()
    {
        $this->model->setImport($this->createMock(Import::class));

        $result = $this->model->toArray();

        $this->assertArrayHasKey(Import::BEHAVIOR_APPEND, $result);
        $this->assertNotEmpty($result[Import::BEHAVIOR_APPEND]->getText());
    }

    public function testSetImport()
    {
        $importMock = $this->createMock(Import::class);

        $this->assertInstanceOf(Basic::class, $this->model->setImport($importMock));
    }

}
