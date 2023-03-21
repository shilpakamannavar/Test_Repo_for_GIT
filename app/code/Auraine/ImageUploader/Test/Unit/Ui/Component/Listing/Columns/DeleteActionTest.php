<?php
namespace Auraine\ImageUploader\Test\Unit\Ui\Component\Listing\Columns;

use Auraine\ImageUploader\Ui\Component\Listing\Columns\DeleteAction;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\TestCase;

class DeleteActionTest extends TestCase
{
    /**
     * @var DeleteAction
     */
    private $deleteAction;

    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var UiComponentFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $uiComponentFactoryMock;

    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);

        $this->deleteAction = new DeleteAction(
            $this->contextMock,
            $this->uiComponentFactoryMock,
            $this->urlBuilderMock,
            [],
            []
        );
    }

    public function testPrepareDataSource()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'image_id' => 1
                    ]
                ]
            ]
        ];

        $viewUrlPath = 'module/controller/view';
        $urlEntityParamName = 'id';
        $expectedUrl = 'http://example.com/module/controller/view/id/1';
        $expectedResult = $dataSource;
        $expectedResult['data']['items'][0]['delete'] = [
            'view' => [
                'href' => $expectedUrl,
                'label' => __('Delete'),
                'confirm' => [
                    'title' => __('Delete Record '),
                    'message' => __('Are you sure?')
                ]
            ]
        ];

        $this->assertIsArray($this->deleteAction->prepareDataSource($dataSource), 'confirm');
    }
}


