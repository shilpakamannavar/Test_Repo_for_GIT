<?php
namespace Auraine\DiscountPercentageFilter\Test\Unit\Observer;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage
 */
class UpdateDiscountPercentageTest extends TestCase
{
    /**
     * Mock dataHelper
     *
     * @var \Auraine\DiscountPercentageFilter\Helper\Data|PHPUnit\Framework\MockObject\MockObject
     */
    private $dataHelper;

    /**
     * Mock action
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action|PHPUnit\Framework\MockObject\MockObject
     */
    private $action;

    /**
     * Mock productRepository
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepository;

    /**
     * Mock storeManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManager;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->dataHelper = $this->createMock(\Auraine\DiscountPercentageFilter\Helper\Data::class);
        $this->action = $this->createMock(\Magento\Catalog\Model\ResourceModel\Product\Action::class);
        $this->productRepository = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\DiscountPercentageFilter\Observer\UpdateDiscountPercentage::class,
            [
                $this->dataHelper,
                $this->action,
                $this->productRepository,
                $this->storeManager,
            ]
        );
    }

    /**
     * @param $discountPercentage
     * @return string
     */
    public function getDiscountVarTest($discountPercentage)
    {
        switch ($discountPercentage) {
            case ($discountPercentage >= 10 && $discountPercentage <20):
                $discountVar = '10% and above';
                break;
            case ($discountPercentage >= 20 && $discountPercentage <30):
                $discountVar = '20% and above';
                break;
            case ($discountPercentage >= 30 && $discountPercentage <40):
                $discountVar = '30% and above';
                break;
            case ($discountPercentage >= 40 && $discountPercentage <50):
                $discountVar = '40% and above';
                break;
            case ($discountPercentage >= 50 && $discountPercentage <60):
                $discountVar = '50% and above';
                break;
            case ($discountPercentage >= 60 && $discountPercentage <70):
                $discountVar = '60% and above';
                break;
            case ($discountPercentage >= 70 && $discountPercentage <80):
                $discountVar = '70% and above';
                break;
            case ($discountPercentage >= 80 && $discountPercentage <90):
                $discountVar = '80% and above';
                break;
            case ($discountPercentage >= 90 && $discountPercentage <= 100):
                $discountVar = '90% and above';
                break;
            default:
                $discountVar = null;
        }
        return $discountVar;
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        $product['sku'] = "Stick6";
        $product['price'] = "450.00";
        $product['special_price'] = "400.00";
        $productType = 'simple';
        if (!empty($product['sku'])) {
            $productPrice =  $product['price'];
            $productSpPrice =  $product['special_price'];
            if ($productPrice !=0 && $productPrice != null && $productType == 'simple') {
                $discountPercentage = 100 - round(($productSpPrice / $productPrice)*100);
                $discountVar = $this->getDiscountVarTest($discountPercentage);
                return [
                    'Testcase 1' => [
                        'prerequisites' => ['param' => $discountVar],
                        'expectedResult' => ['param' => '10% and above']
                    ]
                ];
            }
        }
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetStoreIds()
    {
        $storeId = 0;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $storeId],
                'expectedResult' => ['param' => 0]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetStoreIds
     */
    public function testGetStoreIds(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
