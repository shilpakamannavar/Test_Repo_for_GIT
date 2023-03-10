<?php
// @codingStandardsIgnoreFile
namespace Auraine\Staticcontent\Test\Unit\Model\Config\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
/**
 * @covers \Auraine\Staticcontent\Model\Config\Source\TypeName
 */
class TypeNameTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Backend\Block\Template\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Mock typeNameInstance
     *
     * @var \Auraine\Staticcontent\Model\ResourceModel\Type\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $typeNameInstance;

    /**
     * Mock typeName
     *
     * @var \Auraine\Staticcontent\Model\ResourceModel\Type\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $typeName;

    /**
     * Mock data
     *
     * @var \array|PHPUnit\Framework\MockObject\MockObject
     */
    private $data;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\Staticcontent\Model\Config\Source\TypeName
     */
    private $testObject;

    

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->typeNameInstance = $this->createMock(\Auraine\Staticcontent\Model\ResourceModel\Type\Collection::class);
        $this->typeName = $this->createMock(\Auraine\Staticcontent\Model\ResourceModel\Type\CollectionFactory::class);
        $this->typeName->method('create')->willReturn($this->typeNameInstance);
         $this->testObject = $this->objectManager->getObject(
             \Auraine\Staticcontent\Model\Config\Source\TypeName::class,
             [
                'context' => $this->context,
                'typeName' => $this->typeName,
             ]
         );
    }

    /**
     * @return array
     */
    public function dataProviderForTestToOptionArray()
    {
           $result[] = [
                 'value' => 'loyality', 
                 'label' => 'loyality'
             ];
        $expect = $result;
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' =>  $expect],
                'expectedResult' => ['param' =>  $expect]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestToOptionArray
     */
    public function testToOptionArray(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
