<?php
namespace Auraine\ImageUploader\Test\Unit\Block\Adminhtml\Form;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Auraine\ImageUploader\Block\Adminhtml\Form\UploadButton
 */
class UploadButtonTest extends TestCase
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Auraine\ImageUploader\Block\Adminhtml\Form\UploadButton
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);

        $this->testObject = $this->objectManager->getObject(
        \Auraine\ImageUploader\Block\Adminhtml\Form\UploadButton::class,
            [

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
}
