<?php
namespace Auraine\AddFreeProduct\Plugin;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Quote\Model\MaskedQuoteIdToQuoteId;
use Magento\Framework\GraphQl\Query\Resolver\ArgumentsProcessorInterface;

class BeforeRemoveItemFromCart
{

    /**
     * @var MaskedQuoteIdToQuoteId
     */
    private $maskedQuoteIdToQuoteId;

    /**
     * @var ArgumentsProcessorInterface
     */
    private $argsSelection;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Amasty\Promo\Helper\Item
     */
    private $promoItemHelper;

    /**
     * @var \Amasty\Promo\Model\Registry
     */
    private $promoRegistry;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param MaskedQuoteIdToQuoteId $maskedQuoteIdToQuoteId
     * @param ArgumentsProcessorInterface $argsSelection
     * @param \Amasty\Promo\Helper\Item $promoItemHelper
     * @param \Amasty\Promo\Model\Registry $promoRegistry
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        MaskedQuoteIdToQuoteId $maskedQuoteIdToQuoteId,
        ArgumentsProcessorInterface $argsSelection,
        \Amasty\Promo\Helper\Item $promoItemHelper,
        \Amasty\Promo\Model\Registry $promoRegistry
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->argsSelection = $argsSelection;
        $this->promoItemHelper = $promoItemHelper;
        $this->promoRegistry = $promoRegistry;
    }

    /**
     * Before plugin validates the incoming request and its header for mobile specific coupons.
     *
     * @param \Magento\QuoteGraphQl\Model\Resolver\RemoveItemFromCart $subject
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array $value = null
     * @param array $args = null\Magento\Quote\Model\CouponManagement $subject
     *
     * @return $this
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     * @throws NoSuchEntityException
     */
    public function beforeResolve(
        \Magento\QuoteGraphQl\Model\Resolver\RemoveItemFromCart $subject,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        $processedArgs = $this->argsSelection->process($info->fieldName, $args);
        if (empty($processedArgs['input']['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing.'));
        }

        $maskedCartId = $processedArgs['input']['cart_id'];

        try {
            $cartId = $this->maskedQuoteIdToQuoteId->execute($maskedCartId);
        } catch (NoSuchEntityException $exception) {
            throw new GraphQlNoSuchEntityException(
                __('Could not find a cart with ID "%masked_cart_id"', ['masked_cart_id' => $maskedCartId])
            );
        }

        if (empty($processedArgs['input']['cart_item_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_item_id" is missing.'));
        }
        $itemId = $processedArgs['input']['cart_item_id'];

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $item = $quote->getItemById($itemId);

        if (!$item) {
            throw new NoSuchEntityException(
                __('The %1 Cart doesn\'t contain the %2 item.', $cartId, $itemId)
            );
        }
        
        // Additional request checks to mark only explicitly deleted items
        if (!$item->getParentId() && $this->promoItemHelper->isPromoItem($item)) {
            $this->promoRegistry->deleteProduct($item);
        }
    }
}
