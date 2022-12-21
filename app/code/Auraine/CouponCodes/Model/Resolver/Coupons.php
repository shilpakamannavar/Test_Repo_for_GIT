<?php
declare(strict_types=1);

namespace Auraine\CouponCodes\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;

class Coupons implements ResolverInterface
{

    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Sales Rules collection.
     *
     * @var \Auraine\CouponCodes\Model\DataProvider\Collection
     */
    protected $ruleCollection;

    /**
     * @var MaskedQuoteIdToQuoteIdInterface
     */
    private $maskedQuoteIdToQuoteId;

    /**
     * Constructs a coupon read service object.
     */
    public function __construct(
        QuoteIdMask $quoteIdMaskFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Auraine\CouponCodes\Model\DataProvider\Collection $ruleCollection,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
        )
    {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quoteRepository = $quoteRepository;
        $this->ruleCollection = $ruleCollection;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        
        $cartId = $this->getCartId($args);
        $cartId = $this->maskedQuoteIdToQuoteId->execute($cartId);
        
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);

        $data = $this->ruleCollection->getValidCouponList($quote);

        return $data;

    }

    /**
     * Extracting cart_id from request payload.
     *
     * @param array $args
     * @return string
     */
    private function getCartId($args)
    {
        if (!isset($args['cart_id'])) {
            throw new GraphQlInputException(__('"Cart id should be specified'));
        }
        
        return $args['cart_id'];
    }
}