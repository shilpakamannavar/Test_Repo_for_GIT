<?php

namespace Auraine\Schedule\Test\Unit\Ui\Component\Listing\Column;

use Auraine\Schedule\Ui\Component\Listing\Column\ScheduleActions;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\TestCase;

class ScheduleActionsTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ScheduleActions
     */
    private $scheduleActions;

    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * @var UiComponentFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $uiComponentFactory;

    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilder;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->context = $this->getMockBuilder(ContextInterface::class)
            ->getMock();

        $this->uiComponentFactory = $this->getMockBuilder(UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilder = $this->getMockBuilder(UrlInterface::class)
            ->getMock();

        $this->scheduleActions = $this->objectManager->getObject(ScheduleActions::class, [
            'context' => $this->context,
            'uiComponentFactory' => $this->uiComponentFactory,
            'urlBuilder' => $this->urlBuilder,
            'data' => [],
        ]);
    }

    /**
     * @dataProvider prepareDataSourceProvider
     *
     * @param array $dataSource
     * @param array $expectedResult
     */
    public function testPrepareDataSource(array $dataSource, array $expectedResult)
    {
        $this->urlBuilder->expects($this->any())
            ->method('getUrl')
            ->willReturnCallback(function ($path, $params) {
                return 'http://example.com/' . $path . '?' . http_build_query($params);
            });

        $this->assertSame($expectedResult, $this->scheduleActions->prepareDataSource($dataSource));
    }

    /**
     * @return array
     */
    public function prepareDataSourceProvider()
    {
        return [
            [
                [
                    'data' => [
                        'items' => [
                            [
                                'schedule_id' => 1,
                            ],
                            [
                                'schedule_id' => 2,
                            ],
                        ],
                    ],
                ],
                [
                    'data' => [
                        'items' => [
                            [
                                'schedule_id' => 1,
                                'schedule_actions' => [
                                    'edit' => [
                                        'href' => 'http://example.com/auraine_schedule/schedule/edit?schedule_id=1',
                                        'label' => 'Edit',
                                    ],
                                    'delete' => [
                                        'href' => 'http://example.com/auraine_schedule/schedule/delete?schedule_id=1',
                                        'label' => 'Delete',
                                        'confirm' => [
                                            'title' => 'Delete schedule id 1',
                                            'message' => 'Are you sure you want to delete a schedule id 1 record?',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'schedule_id' => 2,
                                'schedule_actions' => [
                                    'edit' => [
                                        'href' => 'http://example.com/auraine_schedule/schedule/edit?schedule_id=2',
                                        'label' => 'Edit',
                                    ],
                                    'delete' => [
                                        'href' => 'http://example.com/auraine_schedule/schedule/delete?schedule_id=2',
                                        'label' => 'Delete',
                                        'confirm' => [
                                            'title' => 'Delete schedule id 2',
                                            'message' => 'Are you sure you want to delete a schedule id 2 record?',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],  

            ],
        ];  
        
    }
} 
