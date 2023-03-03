<?php
namespace Auraine\AddFreeProduct\Test\Unit\Model\DataProvider;

use Amasty\Promo\Model\ItemRegistry\PromoItemRegistry;
use Auraine\AddFreeProduct\Model\DataProvider\PromoValidator;
use PHPUnit\Framework\TestCase;
use Amasty\Promo\Model\ItemRegistry\PromoItemData;

class PromoValidatorTest extends TestCase
{
    /**
     * Mock promoItemRegistryMock
     *
     * @var PromoItemRegistry
     */
    private $promoItemRegistryMock;

    /**
     * Mock checkoutSessionMock
     *
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSessionMock;

    /**
     * Mock maskedQuoteInterfaceMock
     *
     * @var \Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface
     */
    private $maskedQuoteInterfaceMock;

    /**
     * Mock quoteFactoryMock
     *
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactoryMock;

    /**
     * Mock customerSessionMock
     *
     * @var \Magento\Customer\Model\Session
     */
    private $customerSessionMock;

    /**
     * Mock promoValidator
     *
     * @var PromoValidator
     */
    private $promoValidator;

    /**
     * Main set up method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->promoItemRegistryMock = $this->getMockBuilder(PromoItemRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->maskedQuoteInterfaceMock = $this->getMockBuilder(
            \Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->quoteFactoryMock = $this->getMockBuilder(\Magento\Quote\Model\QuoteFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerSessionMock = $this->getMockBuilder(\Magento\Customer\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->promoValidator = new PromoValidator(
            $this->promoItemRegistryMock,
            $this->checkoutSessionMock,
            $this->maskedQuoteInterfaceMock,
            $this->quoteFactoryMock,
            $this->customerSessionMock
        );
    }

    /**
     * testGetPromoDataItemWithRuleId
     *
     * @return void
     */
    public function testGetPromoDataItemWithRuleId()
    {
        $sku = 'test_sku';
        $ruleId = 123;
        $promoItemDataMock = $this->getMockBuilder(\Amasty\Promo\Model\ItemRegistry\PromoItemData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $promoItemDataMock->expects($this->once())
            ->method('getQtyToProcess')
            ->willReturn(1);
        $this->promoItemRegistryMock->expects($this->once())
            ->method('getItemBySkuAndRuleId')
            ->with($sku, $ruleId)
            ->willReturn($promoItemDataMock);

        $promoValidator = new PromoValidator(
            $this->promoItemRegistryMock,
            $this->checkoutSessionMock,
            $this->maskedQuoteInterfaceMock,
            $this->quoteFactoryMock,
            $this->customerSessionMock
        );
        $result = $promoValidator->getPromoDataItem($sku, ['rule_id' => $ruleId]);
        $this->assertEquals($promoItemDataMock, $result);
    }

    /**
     * testGetPromoDataItemWithoutRuleId
     *
     * @return void
     */
    public function testGetPromoDataItemWithoutRuleId()
    {
        $sku = 'test_sku';
        $promoItemDataMock = $this->getMockBuilder(\Amasty\Promo\Model\ItemRegistry\PromoItemData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $promoItemDataMock->expects($this->once())
            ->method('getQtyToProcess')
            ->willReturn(1);
        $this->promoItemRegistryMock->expects($this->once())
            ->method('getItemsBySku')
            ->with($sku)
            ->willReturn([$promoItemDataMock]);

        $promoValidator = new PromoValidator(
            $this->promoItemRegistryMock,
            $this->checkoutSessionMock,
            $this->maskedQuoteInterfaceMock,
            $this->quoteFactoryMock,
            $this->customerSessionMock
        );

        $result = $promoValidator->getPromoDataItem($sku, []);
        $this->assertEquals($promoItemDataMock, $result);
    }

    /**
     * testGetQtyToAdd
     *
     * @return void
     */
    public function testGetQtyToAdd()
    {
        $promoDataItem = $this->createMock(PromoItemData::class);
        $promoDataItem->method('getQtyToProcess')->willReturn(10);

        $productId = 123;

        $params = [
            'ampromo_qty_select_123' => 5,
        ];

        $qty = $this->promoValidator->getQtyToAdd($promoDataItem, $params, $productId);

        $this->assertEquals(5, $qty);
    }

    /**
     * testIsPromoItemsAddedInQuote
     *
     * @return void
     */
    public function testIsPromoItemsAddedInQuote()
    {
        $promoValidator = new PromoValidator(
            $this->createMock(PromoItemRegistry::class),
            $this->createMock(\Magento\Checkout\Model\Session::class),
            $this->createMock(\Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface::class),
            $this->createMock(\Magento\Quote\Model\QuoteFactory::class),
            $this->createMock(\Magento\Customer\Model\Session::class)
        );

        // Test case where no items were added to the quote
        $items = [];
        $itemsForAdd = ['sku1', 'sku2'];
        $result = $promoValidator->isPromoItemsAddedInQuote($items, $itemsForAdd);
        $this->assertFalse($result);

        // Test case where one item was added to the quote
        $items = [$this->createMock(\Magento\Quote\Model\Quote\Item::class)];
        $items[0]->method('getProduct')->willReturn($this->createMock(\Magento\Catalog\Model\Product::class));
        $items[0]->getProduct()->method('getData')->willReturnMap([
            ['sku', null, 'sku1'],
        ]);
        $itemsForAdd = ['sku1', 'sku2'];
        $result = $promoValidator->isPromoItemsAddedInQuote($items, $itemsForAdd);
        $this->assertTrue($result);

        // Test case where multiple items were added to the quote
        $items = [
            $this->createMock(\Magento\Quote\Model\Quote\Item::class),
            $this->createMock(\Magento\Quote\Model\Quote\Item::class),
        ];
        $items[0]->method('getProduct')->willReturn($this->createMock(\Magento\Catalog\Model\Product::class));
        $items[0]->getProduct()->method('getData')->willReturnMap([
            ['sku', null, 'sku1'],
        ]);
        $items[1]->method('getProduct')->willReturn($this->createMock(\Magento\Catalog\Model\Product::class));
        $items[1]->getProduct()->method('getData')->willReturnMap([
            ['sku', null, 'sku3'],
        ]);
        $itemsForAdd = ['sku1', 'sku2'];
        $result = $promoValidator->isPromoItemsAddedInQuote($items, $itemsForAdd);
        $this->assertTrue($result);
    }
}
