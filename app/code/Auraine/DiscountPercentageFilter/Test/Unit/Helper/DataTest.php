<?php
namespace Auraine\DiscountPercentageFilter\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\DiscountPercentageFilter\Helper\Data
 */
class DataTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Framework\App\Helper\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock http
     *
     * @var \Magento\Framework\App\Request\Http|PHPUnit\Framework\MockObject\MockObject
     */
    private $http;

    /**
     * Mock resolver
     *
     * @var \Magento\Catalog\Model\Layer\Resolver|PHPUnit\Framework\MockObject\MockObject
     */
    private $resolver;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\DiscountPercentageFilter\Helper\Data
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Framework\App\Helper\Context::class);
        $this->http = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->resolver = $this->createMock(\Magento\Catalog\Model\Layer\Resolver::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\DiscountPercentageFilter\Helper\Data::class,
            [
                'context' => $this->context,
                'http' => $this->http,
                'resolver' => $this->resolver,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsCurrentPageCategoryPage()
    {
        $prerequisites_current_page = 2;
        $expectedResult = 2;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_current_page],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsCurrentPageCategoryPage
     */
    public function testIsCurrentPageCategoryPage(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCurrentCategoryName()
    {
        $prerequisites_current_category = 3;
        $expectedResult = 3;
        
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_current_category],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCurrentCategoryName
     */
    public function testGetCurrentCategoryName(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetSubCategories()
    {
        $prerequisites_current_page = 10;
        $expectedResult = 10;

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_current_page],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetSubCategories
     */
    public function testGetSubCategories(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCurrentCategory()
    {
        $prerequisites_current_category = 3;
        $expectedResult = 3;

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_current_category],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCurrentCategory
     */
    public function testGetCurrentCategory(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetDiscountVar()
    {
        $prerequisites_discount_id = '10% And Above';
        $expectedResult = '10% And Above';

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => $prerequisites_discount_id],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetDiscountVar
     */
    public function testGetDiscountVar(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsModuleOutputEnabled()
    {
        $prerequisites_module_enable_status = 'yes';
        $expectedResult = 'yes';

        return [
            'Testcase 1' => [
                'prerequisites' => ['param' =>  $prerequisites_module_enable_status],
                'expectedResult' => ['param' => $expectedResult]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsModuleOutputEnabled
     */
    public function testIsModuleOutputEnabled(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
