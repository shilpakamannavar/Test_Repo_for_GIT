<?php
namespace Auraine\ImageUploader\Test\Unit\Ui\Component\Form;

use Auraine\ImageUploader\Model\ResourceModel\Image\Collection;
use Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory;
use Auraine\ImageUploader\Ui\Component\Form\DataProvider;
use Magento\Framework\Registry;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    private $registryMock;
    private $imageCollectionFactoryMock;
    private $dataProvider;
    private $metaMock;
    private $dataMock;

    protected function setUp(): void
    {
        $this->registryMock = $this->createMock(Registry::class);
        $this->imageCollectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->metaMock = $this->createMock(ModifierInterface::class);
        $this->dataMock = $this->getMockBuilder(\Magento\Framework\Api\Search\DocumentInterface::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->dataProvider = new DataProvider(
            'imageuploader_form_data_source',
            'image_id',
            'image_id',
            $this->registryMock,
            $this->imageCollectionFactoryMock,
            ['meta' => $this->metaMock],
            ['data' => $this->dataMock]
        );
    }

    public function testGetData()
    {
        $result = $this->dataProvider->getData();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
