<?php
declare(strict_types=1);

namespace Auraine\Schedule\Test\Unit\Controller\Adminhtml\Schedule;

use Auraine\Schedule\Controller\Adminhtml\Schedule\InlineEdit;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class InlineEditTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $contextMock;

    /**
     * @var Http|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var JsonFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $jsonFactoryMock;

    /**
     * @var Json|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $jsonMock;

    /**
     * @var InlineEdit
     */
    protected $controller;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = $this->objectManager->getObject(
            InlineEdit::class,
            [
                'context' => $this->contextMock,
                'jsonFactory' => $this->jsonFactory->create()
            ]
        );
    }
}
