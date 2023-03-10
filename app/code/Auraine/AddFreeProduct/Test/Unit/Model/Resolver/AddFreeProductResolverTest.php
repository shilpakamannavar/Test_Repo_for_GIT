<?php
namespace Auraine\AddFreeProduct\Test\Unit\Model\Resolver;

use Amasty\Promo\Helper\Cart;
use Amasty\Promo\Model\Registry;
use Auraine\AddFreeProduct\Model\DataProvider\PromoValidator;
use Auraine\AddFreeProduct\Model\Resolver\AddFreeProductResolver;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\QuoteRepository;
use PHPUnit\Framework\TestCase;

class AddFreeProductResolverTest extends TestCase
{
    /**
     * AddFreeProductResolver object
     *
     * @var AddFreeProductResolver
     */
    private AddFreeProductResolver $resolver;

    /**
     * Mock promoRegistryMock
     *
     * @var Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    private Registry|\PHPUnit\Framework\MockObject\MockObject $promoRegistryMock;

    /**
     * Mock promoCartHelperMock
     *
     * @var Cart|\PHPUnit\Framework\MockObject\MockObject
     */
    private Cart|\PHPUnit\Framework\MockObject\MockObject $promoCartHelperMock;

    /**
     * Mock productRepositoryMock
     *
     * @var ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject $productRepositoryMock;

    /**
     * Mock checkoutSessionMock
     *
     * @var Session|\PHPUnit\Framework\MockObject\MockObject
     */
    private Session|\PHPUnit\Framework\MockObject\MockObject $checkoutSessionMock;

    /**
     * Mock quoteRepositoryMock
     *
     * @var QuoteRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private QuoteRepository|\PHPUnit\Framework\MockObject\MockObject $quoteRepositoryMock;

    /**
     * Mock promoValidatorMock
     *
     * @var PromoValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    private PromoValidator|\PHPUnit\Framework\MockObject\MockObject $promoValidatorMock;

    /**
     * Main setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->promoRegistryMock = $this->createMock(Registry::class);
        $this->promoCartHelperMock = $this->createMock(Cart::class);
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->checkoutSessionMock = $this->createMock(Session::class);
        $this->quoteRepositoryMock = $this->createMock(QuoteRepository::class);
        $this->promoValidatorMock = $this->createMock(PromoValidator::class);

        $this->resolver = new AddFreeProductResolver(
            $this->promoRegistryMock,
            $this->promoCartHelperMock,
            $this->productRepositoryMock,
            $this->checkoutSessionMock,
            $this->quoteRepositoryMock,
            $this->promoValidatorMock
        );
    }

    /**
     * testResolveThrowsGraphQlInputExceptionWhenCartIdIsMissing
     *
     * @return void
     */
    public function testResolveThrowsGraphQlInputExceptionWhenCartIdIsMissing(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cartId" is missing');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = [];

        $this->resolver->resolve($field, $context, $info, $value, $args);
    }

    /**
     * testResolveThrowsGraphQlInputExceptionWhenCartItemsAreMissing
     *
     * @return void
     */
    public function testResolveThrowsGraphQlInputExceptionWhenCartItemsAreMissing(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cartItems" is missing');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = [
            'cartId' => '123'
        ];

        $this->resolver->resolve($field, $context, $info, $value, $args);
    }
}
