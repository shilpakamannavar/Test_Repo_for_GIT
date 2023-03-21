<?php

namespace Auraine\Schedule\Test\Unit\Model\Config\Source;

use Auraine\Schedule\Model\Config\Source\StatusDropdownValues;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class StatusDropdownValuesTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var StatusDropdownValues
     */
    private $model;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->model = $this->objectManager->getObject(StatusDropdownValues::class);
    }

    public function testGetAllOptions()
    {
        $expectedOptions = [
            [
                'value' => 'Pending',
                'label' => __('Pending'),
            ],
            [
                'value' => 'Active',
                'label' => __('Active'),
            ],
            [
                'value' => 'Inactive',
                'label' => __('Inactive'),
            ],
        ];

        $this->assertEquals($expectedOptions, $this->model->getAllOptions());
    }
}
