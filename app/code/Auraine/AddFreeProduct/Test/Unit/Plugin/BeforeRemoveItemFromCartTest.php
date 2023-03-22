<?php

namespace Auraine\AddFreeProduct\Plugin\Test\Unit;

use Auraine\AddFreeProduct\Plugin\BeforeRemoveItemFromCart;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ArgumentsProcessorInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteId;
use Magento\QuoteGraphQl\Model\Resolver\RemoveItemFromCart;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Amasty\Promo\Helper\Item as PromoItemHelper;
use Amasty\Promo\Model\Registry as PromoRegistry;

class BeforeRemoveItemFromCartTest extends TestCase
{
    /**
     * @var CartRepositoryInterface|MockObject
     */
    private $quoteRepositoryMock;

    /**
     * @var MaskedQuoteIdToQuoteId|MockObject
     */
    private $maskedQuoteIdToQuoteIdMock;

    /**
     * @var ArgumentsProcessorInterface|MockObject
     */
    private $argsSelectionMock;

    /**
     * @var PromoItemHelper|MockObject
     */
    private $promoItemHelperMock;

    /**
     * @var PromoRegistry|MockObject
     */
    private $promoRegistryMock;

    /**
     * @var BeforeRemoveItemFromCart
     */
    private $plugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->quoteRepositoryMock = $this->createMock(CartRepositoryInterface::class);
        $this->maskedQuoteIdToQuoteIdMock = $this->createMock(MaskedQuoteIdToQuoteId::class);
        $this->argsSelectionMock = $this->createMock(ArgumentsProcessorInterface::class);
        $this->promoItemHelperMock = $this->createMock(PromoItemHelper::class);
        $this->promoRegistryMock = $this->createMock(PromoRegistry::class);

        $this->plugin = new BeforeRemoveItemFromCart(
            $this->quoteRepositoryMock,
            $this->maskedQuoteIdToQuoteIdMock,
            $this->argsSelectionMock,
            $this->promoItemHelperMock,
            $this->promoRegistryMock
        );
    }

    public function testResolveWhenRequiredParameterMaskedCartIdIsMissing()
    {
        $this->expectException(GraphQlNoSuchEntityException::class);
        $this->expectExceptionMessage('Could not find a cart with ID "test_cart_id"');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $info->fieldName = 'field';
        $args = ['input' => ['cart_id' => 'test_cart_id']];

        $this->argsSelectionMock
            ->expects($this->once())
            ->method('process')
            ->with($info->fieldName, $args)
            ->willReturn($args);

        $this->maskedQuoteIdToQuoteIdMock->method('execute')
            ->with('test_cart_id')
            ->willThrowException(new GraphQlNoSuchEntityException(__('Could not find a cart with ID "test_cart_id"')));

        $this->plugin->beforeResolve(
            $this->createMock(RemoveItemFromCart::class),
            $field,
            $context,
            $info,
            $value,
            $args
        );
    }

    public function testResolveWhenRequiredParameterCartIdIsMissing()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cart_id" is missing.');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = ['input' => []];
        $info->fieldName = 'field';

        $this->argsSelectionMock
            ->expects($this->once())
            ->method('process')
            ->with($info->fieldName, $args)
            ->willReturn(['input' => []]);

        $this->plugin->beforeResolve(
            $this->createMock(RemoveItemFromCart::class),
            $field,
            $context,
            $info,
            $value,
            $args
        );
    }

    public function testResolveWhenRequiredParameterCartItemIdIsMissing()
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage('Required parameter "cart_item_id" is missing.');
        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = ['input' => ['cart_id' => 'test_cart_id']];
        $info->fieldName = 'field';
        $cartId = 1;

        $this->argsSelectionMock
            ->expects($this->once())
            ->method('process')
            ->with($info->fieldName, $args)
            ->willReturn(['input' => ['cart_id' => 'test_cart_id']]);

        $this->maskedQuoteIdToQuoteIdMock->method('execute')
            ->with('test_cart_id')
            ->willReturn($cartId);

        $this->plugin->beforeResolve(
            $this->createMock(RemoveItemFromCart::class),
            $field,
            $context,
            $info,
            $value,
            $args
        );
    }

    public function testResolveWhenCartItemIsNotInCart()
    {
        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('The 1 Cart doesn\'t contain the 123 item.');

        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = ['input' => ['cart_id' => 'test_cart_id', 'cart_item_id' => 123]];
        $info->fieldName = 'field';
        $cartId = 1;

        $this->argsSelectionMock
            ->expects($this->once())
            ->method('process')
            ->with($info->fieldName, $args)
            ->willReturn(['input' => ['cart_id' => 'test_cart_id', 'cart_item_id' => 123]]);

        $quoteMock = $this->createMock(Quote::class);

        $quoteMock
            ->expects($this->once())
            ->method('getItemById')
            ->with(123)
            ->willReturn(null);

        $this->maskedQuoteIdToQuoteIdMock->method('execute')
            ->with('test_cart_id')
            ->willReturn($cartId);

        $this->quoteRepositoryMock
            ->expects($this->once())
            ->method('getActive')
            ->with(1)
            ->willReturn($quoteMock);

        $this->plugin->beforeResolve(
            $this->createMock(RemoveItemFromCart::class),
            $field,
            $context,
            $info,
            $value,
            $args
        );
    }
    public function testResolve()
    {
        $field = $this->createMock(Field::class);
        $context = [];
        $info = $this->createMock(ResolveInfo::class);
        $value = null;
        $args = ['input' => ['cart_id' => 'test_cart_id', 'cart_item_id' => 123]];
        $info->fieldName = 'field';
        $cartId = 1;

        $this->argsSelectionMock
            ->expects($this->once())
            ->method('process')
            ->with($info->fieldName, $args)
            ->willReturn(['input' => ['cart_id' => 'test_cart_id', 'cart_item_id' => 123]]);

        $quoteMock = $this->createMock(Quote::class);

        $itemMock = $this->createMock(Item::class);

        $quoteMock
            ->expects($this->once())
            ->method('getItemById')
            ->with(123)
            ->willReturn($itemMock);

        $itemMock->method('__call')->with('getParentId')->willReturn(false);

        $this->promoItemHelperMock->method('isPromoItem')->willReturn(true);

        $this->maskedQuoteIdToQuoteIdMock->method('execute')
            ->with('test_cart_id')
            ->willReturn($cartId);

        $this->quoteRepositoryMock
            ->expects($this->once())
            ->method('getActive')
            ->with(1)
            ->willReturn($quoteMock);

        $this->plugin->beforeResolve(
            $this->createMock(RemoveItemFromCart::class),
            $field,
            $context,
            $info,
            $value,
            $args
        );
    }
}
