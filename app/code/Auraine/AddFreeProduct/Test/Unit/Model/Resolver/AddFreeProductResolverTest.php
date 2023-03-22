<?php

namespace Auraine\AddFreeProduct\Test\Unit\Model\Resolver;

use PHPUnit\Framework\TestCase;
use Auraine\AddFreeProduct\Model\Resolver\AddFreeProductResolver;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\LocalizedException;

/**
 * Summary of AddFreeProductResolverTest
 */
class AddFreeProductResolverTest extends TestCase
{
    /**
     * @var \Amasty\Promo\Model\Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $promoRegistryMock;

    /**
     * @var \Amasty\Promo\Helper\Cart|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $promoCartHelperMock;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productRepositoryMock;

    /**
     * @var \Magento\Checkout\Model\Session|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $checkoutSessionMock;

    /**
     * @var \Magento\Quote\Model\QuoteRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var \Auraine\AddFreeProduct\Model\DataProvider\PromoValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $promoValidatorMock;

    /**
     * @var AddFreeProductResolver|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resolver;

    /**
     * @var ResolveInfo|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $infoMock;

    /**
     * @var array
     */
    protected $context = [];

    /**
     * Request whitelist parameters
     * @var array
     */
    private $requestOptions = [
        'super_attribute',
        'options',
        'super_attribute',
        'links',
        'giftcard_sender_name',
        'giftcard_sender_email',
        'giftcard_recipient_name',
        'giftcard_recipient_email',
        'giftcard_message',
        'giftcard_amount',
        'custom_giftcard_amount',
        'bundle_option',
        'bundle_option_qty',
    ];

    protected function setUp(): void
    {
        $this->promoRegistryMock = $this->getMockBuilder(\Amasty\Promo\Model\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->promoCartHelperMock = $this->getMockBuilder(\Amasty\Promo\Helper\Cart::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productRepositoryMock = $this->getMockBuilder(\Magento\Catalog\Api\ProductRepositoryInterface::class)
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->quoteRepositoryMock = $this->getMockBuilder(\Magento\Quote\Model\QuoteRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->promoValidatorMock = $this->getMockBuilder(
            \Auraine\AddFreeProduct\Model\DataProvider\PromoValidator::class
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->infoMock = $this->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

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
     * Summary of testResolve
     * @return void
     */
    public function testResolve()
    {
        $args = [
            'cartId' => 123,
            'cartItems' => [
                ['sku' => 'test-sku', 'isPromoItems' => true],
                []
                ]
            ];
        $productId = 1;
        $params = $args['cartItems'];
        $qty = 100;

        $quote = $this->createMock(\Magento\Quote\Model\Quote::class);

        $this->promoValidatorMock->method('getQuote')
            ->with($args)
            ->willReturn($quote);

        $productMock = $this->createMock(\Magento\Catalog\Model\Product::class);
        
        $this->productRepositoryMock->method('get')
            ->with('test-sku')
            ->willReturn($productMock);
        
        $productMock->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $productMock->method('getSku')
            ->willReturn('test-sku');

        $promoItemDataMock = $this->createMock(\Amasty\Promo\Model\ItemRegistry\PromoItemData::class);

        $this->promoValidatorMock->method('getPromoDataItem')
            ->with('test-sku', $params[0])
            ->willReturn($promoItemDataMock);

        $this->promoValidatorMock->method('getQtyToAdd')
            ->with($promoItemDataMock, $params[0], $productId)
            ->willReturn($qty);

        $this->promoCartHelperMock->method('addProduct')
            ->with(
                $productMock,
                $qty,
                $promoItemDataMock,
                array_intersect_key($params, array_flip($this->requestOptions)),
                $quote
            )
            ->willReturn(true);
    
        $itemMock = $this->createMock(\Magento\Quote\Model\Quote\Item::class);
    
        $quote->expects($this->once())
            ->method('getAllItems')
            ->willReturn([$itemMock]);

        $this->promoValidatorMock
            ->expects($this->once())
            ->method('isPromoItemsAddedInQuote')
            ->with()
            ->willReturn(true);

        $this->quoteRepositoryMock->method('save')
            ->with($quote);

        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = null;

        $this->resolver->resolve($fieldMock, $this->context, $this->infoMock, $value, $args);
    }

    /**
     * Summary of testResolveWithMissingCartId
     * @return void
     */
    public function testResolveWithMissingCartId()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cartId" is missing');

        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = null;
        $args = ['cartItems' => [['sku' => 'test-sku']]];

        $this->resolver->resolve($fieldMock, $this->context, $this->infoMock, $value, $args);
    }

    /**
     * Summary of testResolveWithMissingCartItems
     * @return void
     */
    public function testResolveWithMissingCartItems()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cartItems" is missing');

        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = null;
        $args = ['cartId' => 123];

        $this->resolver->resolve($fieldMock, $this->context, $this->infoMock, $value, $args);
    }

    /**
     * Summary of testResolveWithIncorrectSku
     * @return void
     */
    public function testResolveWithIncorrectSku()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage(__('Unable to process the request please try again.'));

        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = null;
        $args = ['cartItems' => [['sku' => 'test-sku']], 'cartId' => 123];

        $quote = $this->createMock(\Magento\Quote\Model\Quote::class);

        $this->promoValidatorMock->method('getQuote')
            ->with($args)
            ->willReturn($quote);
        
        $this->productRepositoryMock
            ->method('get')
            ->with('test-sku')
            ->willThrowException(new GraphQlInputException(__('Unable to process the request please try again.')));

        $this->resolver->resolve($fieldMock, $this->context, $this->infoMock, $value, $args);
    }

    /**
     * Summary of testResolveWithInvalidParams
     * @return void
     */
    public function testResolveWithInvalidParams()
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Free gift couldn\'t be added to the cart.' .
            'Please try again or contact the administrator for more information.');
        
        $fieldMock = $this->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value = null;
        $args = ['cartId' => 123, 'cartItems' => [['sku' => 'test-sku']]];

        $productMock = $this->createMock(\Magento\Catalog\Model\Product::class);
        $quote = $this->createMock(\Magento\Quote\Model\Quote::class);

        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with('test-sku')
            ->willReturn($productMock);
     
        $this->promoValidatorMock->expects($this->once())
            ->method('getQuote')
            ->with($args)
            ->willReturn($quote);
        
        $itemMock = $this->createMock(\Magento\Quote\Model\Quote\Item::class);

        $quote->expects($this->once())
            ->method('getAllItems')
            ->willReturn([$itemMock]);

        $this->resolver->resolve($fieldMock, $this->context, $this->infoMock, $value, $args);
    }

}
