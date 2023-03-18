<?php

namespace Auraine\CsvUploader\Test\Unit\Ui\Component\Form;

use Auraine\CsvUploader\Model\ResourceModel\Csv\CollectionFactory;
use Auraine\CsvUploader\Ui\Component\Form\DataProvider;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    public function testGetDataReturnsEmptyArray()
    {
        $name = 'test_name';
        $primaryFieldName = 'test_id';
        $requestFieldName = 'test_id';
        $registry = $this->createMock(Registry::class);
        $csvCollectionFactory = $this->createMock(CollectionFactory::class);

        $dataProvider = new DataProvider(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $registry,
            $csvCollectionFactory
        );

        $this->assertEquals([], $dataProvider->getData());
    }
}