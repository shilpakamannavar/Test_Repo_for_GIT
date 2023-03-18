<?php
namespace Auraine\Category\Test\Unit\Plugin;

use PHPUnit\Framework\TestCase;
use Auraine\Category\Plugin\CategoryDataProvider;
use Magento\Catalog\Model\Category\DataProvider;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CategoryDataProviderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    /**
     * @var CategoryHelper
     */
    protected $categoryHelperMock;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerMock;
    /**
     * @var DataProvider
     */
    protected $dataProviderMock;
     /**
      * @var CategoryDataProvider
      */
    protected $categoryDataProvider;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->categoryHelperMock = $this->getMockBuilder(CategoryHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();

        $this->dataProviderMock = $this->getMockBuilder(DataProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryDataProvider = $this->objectManager->getObject(
            CategoryDataProvider::class,
            [
                'categoryHelper' => $this->categoryHelperMock,
                'storeManager' => $this->storeManagerMock
            ]
        );
    }

    public function testAfterGetDataWhenGetBaseUrlThrowsException()
    {
        $result = [
                [
                'category_id' => 1,
                'name' => 'Hair',
                'image' => 'hair.jpg',
                ]
        ];

        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->method('getBaseUrl')->willThrowException(new \Exception('Unable to get base URL'));

        $storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();
        $storeManagerMock->method('getStore')->willReturn($storeMock);

        $this->categoryDataProvider = new CategoryDataProvider($this->categoryHelperMock, $storeManagerMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to get base URL');

        $this->categoryDataProvider->afterGetData($this->dataProviderMock, $result);
    }

    public function testAfterGetDataWhenGetStoreThrowsException()
    {
        $result = [
            [
                'category_id' => 1,
                'name' => 'Skincare',
                'image' => 'Skincare.jpg',
            ]
        ];

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willThrowException(new \Exception('Something went wrong.'));

        $this->expectException(\Exception::class);

        $this->categoryDataProvider->afterGetData($this->dataProviderMock, $result);
    }

    /**
     * @return array
     */
    public function dataProviderForTestAfterGetData()
    {

        $this->objectManager = new ObjectManager($this);

        $this->categoryHelperMock = $this->getMockBuilder(CategoryHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();

        $this->dataProviderMock = $this->getMockBuilder(DataProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryDataProvider = $this->objectManager->getObject(
            CategoryDataProvider::class,
            [
                'categoryHelper' => $this->categoryHelperMock,
                'storeManager' => $this->storeManagerMock
            ]
        );

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAfterGetData
     */

    public function testAfterGetData(array $prerequisites, array $expectedResult)
    {
        $result = [
            [
                'category_id' => 1,
                'name' => 'Makeup',
                'image' => 'makeup.jpg',
                'custom_image' => 'makeup1.jpg',
            ]

        ];

        $mediaUrl = 'http://example.com/media/';

            $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->method('getBaseUrl')->willReturn($mediaUrl);
        $storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();
        $storeManagerMock->method('getStore')->willReturn($storeMock);

        $this->categoryDataProvider = new CategoryDataProvider($this->categoryHelperMock, $storeManagerMock);

        foreach ($result as &$category) {
            $category['custom_image'] = $mediaUrl . 'catalog/category/' . $category['image'];
        }

        $category = $result[0];

        $this->assertEquals($result[0], $this->categoryDataProvider->afterGetData($this->dataProviderMock, $category));
    }
}
