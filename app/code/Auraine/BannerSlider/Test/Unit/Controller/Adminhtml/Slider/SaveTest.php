<?php
namespace Auraine\BannerSlider\Test\Unit\Controller\Adminhtml\Slider;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\BannerSlider\Controller\Adminhtml\Slider\Save
 */
class SaveTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\App\Action\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock sliderRepository
     *
     * @var \Auraine\BannerSlider\Api\SliderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $sliderRepository;

    /**
     * Mock dataPersistor
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $dataPersistor;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\BannerSlider\Controller\Adminhtml\Slider\Save
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->sliderRepository = $this->createMock(\Auraine\BannerSlider\Api\SliderRepositoryInterface::class);
        $this->dataPersistor = $this->createMock(\Magento\Framework\App\Request\DataPersistorInterface::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\BannerSlider\Controller\Adminhtml\Slider\Save::class,
            [
                'context' => $this->context,
                'sliderRepository' => $this->sliderRepository,
                'dataPersistor' => $this->dataPersistor,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
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
    public function dataProviderForTestDispatch()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestDispatch
     */
    public function testDispatch(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTest_processUrlKeys()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTest_processUrlKeys
     */
    public function test_processUrlKeys(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetUrl()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetUrl
     */
    public function testGetUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetActionFlag()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetActionFlag
     */
    public function testGetActionFlag(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRequest()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRequest
     */
    public function testGetRequest(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetResponse()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetResponse
     */
    public function testGetResponse(array $prerequisites, array $expectedResult)
    {
        $data=$this->getSliderData();
        $this->assertEquals($data['slider_id'], "slider_id");
        $this->assertEquals($data['title'], "title");
        $this->assertEquals($data['is_show_title'], 1);
        $this->assertEquals($data['additional_information'], "additional_information");
        $this->assertEquals($data['product_ids'], "product_ids");
        $this->assertEquals($data['link'], "link");
        $this->assertEquals($data['sort_order'], "sort_order");
        $this->assertEquals($data['target_type'], "target_type");
        $this->assertEquals($data['slider_type'], "slider_type");
        $this->assertEquals($data['is_enabled'], 1);
        $this->assertEquals($data['category_id'], "category_id");

    }
    

    protected function getSliderData()
      {
       $data = [
        'category_id' =>'category_id',
        'slider_id' =>'slider_id',
        'title' =>'title',
        'product_ids'=>'product_ids',
        'additional_information'=>'additional_information',
        'slider_type'=>'slider_type',
        'target_type'=>'target_type',
        'slider_type'=>'slider_type',
        'link'=>'link',
        'sort_order'=>'sort_order',
        'is_show_title'=> 1,
        'is_enabled'=> 1
        ];
       return $data;
    }
   
}

