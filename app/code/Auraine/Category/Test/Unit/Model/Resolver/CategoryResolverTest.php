<?php
declare(strict_types=1);

namespace Auraine\Category\Test\Unit\Model\Resolver;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Category;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use Auraine\Category\Model\Resolver\CategoryResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Auraine\Category\Model\Resolver\CategoryImageResolver;

class CategoryResolverTest extends TestCase
{
    /**
     * @var CategoryResolver
     */
    private $categoryResolver;

    /**
     * @var CategoryRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryRepositoryMock;

    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;
    /**
     * @var ContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;
    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

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
        $objectManager = new ObjectManager($this);

        $this->categoryRepositoryMock = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->getMock();

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
        $this->categoryResolver = $objectManager->getObject(
            CategoryResolver::class,
            [
                'categoryRepository' => $this->categoryRepositoryMock,
                'urlBuilder' => $this->urlBuilderMock,
            ]
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
