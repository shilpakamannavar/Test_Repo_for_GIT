<?php
namespace Auraine\Category\Test\Unit\Model\Resolver;

use Auraine\Category\Model\Resolver\CategoryImageResolver;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\TestCase;

class CategoryImageResolverTest extends TestCase
{
    /**
     * @var CategoryRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryRepositoryMock;

    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var ResolveInfo|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resolveInfoMock;

    /**
     * @var CategoryImageResolver
     */
    private $resolver;

    protected function setUp(): void
    {
        $this->categoryRepositoryMock = $this->createMock(CategoryRepository::class);
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->resolveInfoMock = $this->createMock(ResolveInfo::class);
        $this->resolver = new CategoryImageResolver(
            $this->categoryRepositoryMock,
            $this->urlBuilderMock,
            $this->scopeConfigMock
        );
    }

    public function testResolve()
    {
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $categoryMock->expects($this->once())
            ->method('getData')
            ->with('category_image_2')
            ->willReturn('path/to/image.jpg');

        $this->urlBuilderMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(['_type' => UrlInterface::URL_TYPE_MEDIA])
            ->willReturn('http://example.com/media/');

            $field = new Field(
                'category_image_2',
                '',
                false,
                false,
                '',
                ''
            );

        $result = $this->resolver->resolve(
            $field,
            $this->contextMock,
            $this->resolveInfoMock,
            ['model' => $categoryMock],
            null
        );

        $this->assertEquals('http://example.com/media/path/to/image.jpg', $result);
    }
}
