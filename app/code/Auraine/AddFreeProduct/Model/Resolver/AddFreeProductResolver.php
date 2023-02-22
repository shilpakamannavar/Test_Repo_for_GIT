<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\AddFreeProduct\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\LocalizedException;

/**
 * Resolver for addProductsToCart mutation
 *
 * @inheritdoc
 */
class AddFreeProductResolver implements ResolverInterface
{

    /**
     * @var \Amasty\Promo\Model\Registry
     */
    protected $promoRegistry;

    /**
     * @var \Amasty\Promo\Helper\Cart
     */
    protected $promoCartHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var \Auraine\AddFreeProduct\Model\DataProvider\PromoValidator
     */
    private $promoValidator;

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

    /**
     * @param \Amasty\Promo\Model\Registry $promoRegistry
     * @param \Amasty\Promo\Helper\Cart $promoCartHelper
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Auraine\AddFreeProduct\Model\DataProvider\PromoValidator $promoValidator
     */
    public function __construct(
        \Amasty\Promo\Model\Registry $promoRegistry,
        \Amasty\Promo\Helper\Cart $promoCartHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Auraine\AddFreeProduct\Model\DataProvider\PromoValidator $promoValidator
    ) {
        $this->promoRegistry = $promoRegistry;
        $this->promoCartHelper = $promoCartHelper;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->promoValidator = $promoValidator;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['cartId'])) {
            throw new GraphQlInputException(__('Required parameter "cartId" is missing'));
        }
        if (empty($args['cartItems']) || !is_array($args['cartItems'])
        ) {
            throw new GraphQlInputException(__('Required parameter "cartItems" is missing'));
        }

        $quote = $this->promoValidator->getQuote($args);
        $cartItems = $args['cartItems'];

        try {

            $itemsForAdd = [];
            $updateTotalQty = false;

            foreach ($cartItems as $params) {
                if (empty($params)) {
                    continue;
                }

                $product = $this->productRepository->get($cartItems[0]['sku']);
                $productId = (int)$product->getId();
                $promoDataItem = $this->promoValidator->getPromoDataItem($params['sku'], $params);

                if ($promoDataItem) {
                    $qty = $this->promoValidator->getQtyToAdd($promoDataItem, $params, $productId);
                    $updateTotalQty = true;
                    $requestOptions = array_intersect_key($params, array_flip($this->requestOptions));
                    $itemsForAdd[] = $product->getSku();

                    $this->promoCartHelper->addProduct(
                        $product,
                        $qty,
                        $promoDataItem,
                        $requestOptions,
                        $quote
                    );
                }
            }

            if ($updateTotalQty) {
                $this->quoteRepository->save($quote);
            }

        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__('Unable to process the request please try again.'));
        }

        if (!$this->promoValidator->isPromoItemsAddedInQuote($quote->getAllItems(), $itemsForAdd)) {
            throw new LocalizedException(__('Free gift couldn\'t be added to the cart.' .
            'Please try again or contact the administrator for more information.'));
        }

        return [
            'status' => 'Success'
        ];
    }
}
