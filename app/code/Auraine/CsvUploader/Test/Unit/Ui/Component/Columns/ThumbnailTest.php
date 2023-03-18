<?php
namespace Auraine\CsvUploader\Test\Unit\Ui\Component\Columns;

use Auraine\CsvUploader\Ui\Component\Columns\Thumbnail;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use PHPUnit\Framework\TestCase;

class ThumbnailTest extends TestCase
{
    /** @var ObjectManager */
    protected $objectManager;

    /** @var Thumbnail */
    protected $thumbnail;

    /** @var ContextInterface */
    protected $context;

    /** @var UiComponentFactory */
    protected $uiComponentFactory;

    /** @var StoreManagerInterface */
    protected $storeManagerInterface;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->context = $this->getMockBuilder(ContextInterface::class)
            ->getMock();

        $this->uiComponentFactory = $this->getMockBuilder(UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerInterface = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();

        $this->thumbnail = $this->objectManager->getObject(
            Thumbnail::class,
            [
                'context' => $this->context,
                'uiComponentFactory' => $this->uiComponentFactory,
                'storeManagerInterface' => $this->storeManagerInterface,
            ]
        );
    }

    public function testPrepareDataSourceReturnsOriginalDataSourceWhenNoPathKeyInItem()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'image_id' => 1,
                    ],
                    [
                        'image_id' => 2,
                    ],
                ],
            ],
        ];
        $storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $contextMock = $this->createMock(ContextInterface::class);
        $uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
    
        $thumbnail = new Thumbnail($contextMock, $uiComponentFactoryMock, $storeManagerMock);
        $result = $thumbnail->prepareDataSource($dataSource);
    
        $this->assertEquals($dataSource, $result);
    }
}