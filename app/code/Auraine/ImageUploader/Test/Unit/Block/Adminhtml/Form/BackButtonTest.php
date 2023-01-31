<?php
namespace Auraine\ImageUploader\Test\Unit\Block\Adminhtml\Form;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ImageUploader\Block\Adminhtml\Form\BackButton
 */
class BackButtonTest extends TestCase
{
    /**
     * Mock urlInterface
     *
     * @var \Magento\Backend\Model\UrlInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $urlInterface;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ImageUploader\Block\Adminhtml\Form\BackButton
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->urlInterface = $this->createMock(\Magento\Backend\Model\UrlInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Auraine\ImageUploader\Block\Adminhtml\Form\BackButton::class,
            [
                'urlInterface' => $this->urlInterface,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetButtonData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetButtonData
     */
    public function testGetButtonData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetBackUrl()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetBackUrl
     */
    public function testGetBackUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
