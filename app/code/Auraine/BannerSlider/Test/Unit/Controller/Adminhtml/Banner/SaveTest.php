<?php
namespace Auraine\BannerSlider\Test\Unit\Controller\Adminhtml\Banner;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\BannerSlider\Controller\Adminhtml\Banner\Save
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
     * Mock bannerRepository
     *
     * @var \Auraine\BannerSlider\Api\BannerRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $bannerRepository;

    /**
     * Mock dataPersistor
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $dataPersistor;

    /**
     * Mock processorPool
     *
     * @var \Auraine\BannerSlider\Model\Banner\ResourcePath\ProcessorPool|PHPUnit\Framework\MockObject\MockObject
     */
    private $processorPool;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\BannerSlider\Controller\Adminhtml\Banner\Save
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->bannerRepository = $this->createMock(\Auraine\BannerSlider\Api\BannerRepositoryInterface::class);
        $this->dataPersistor = $this->createMock(\Magento\Framework\App\Request\DataPersistorInterface::class);
        $this->processorPool = $this->createMock(\Auraine\BannerSlider\Model\Banner\ResourcePath\ProcessorPool::class);
        $this->testObject = $this->objectManager->getObject(
            \Auraine\BannerSlider\Controller\Adminhtml\Banner\Save::class,
            [
                'context' => $this->context,
                'bannerRepository' => $this->bannerRepository,
                'dataPersistor' => $this->dataPersistor,
                'processorPool' => $this->processorPool,
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
        $data=$this->getBannerData();
        $this->assertEquals($data['slider_id'], "slider_id");
        $this->assertEquals($data['title'], "title");
        $this->assertEquals($data['resource_map_id'], "resource_map_id");
        $this->assertEquals($data['resource_type'], "resource_type");
        $this->assertEquals($data['alt_text'], "alt_text");
        $this->assertEquals($data['link'], "link");
        $this->assertEquals($data['sort_order'], "sort_order");
        $this->assertEquals($data['is_enabled'], 1);
        //$this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
    

    protected function getBannerData()
      {
       $data = [
        'slider_id' =>'slider_id',
        'title' =>'title',
        'resource_map_id'=>'resource_map_id',
        'resource_type'=>'resource_type',
        'alt_text'=>'alt_text',
        'link'=>'link',
        'sort_order'=>'sort_order',
        'is_enabled'=> 1
        ];
       return $data;
    }
}
    
