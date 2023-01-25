<?php
declare(strict_types=1);

namespace Auraine\CouponCodes\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Auraine\CouponCodes\Helper\Data;

class Coupons implements ResolverInterface
{
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
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager;

    /**
     * Constructs a coupon read service object.
     *
     * @param QuoteIdMask $quoteIdMaskFactory
     * @param \Auraine\CouponCodes\Model\DataProvider\Collection $ruleCollection
     * @param MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
     * @param \Magento\Framework\ObjectManagerInterface $objectManger
     */
    public function __construct(
        QuoteIdMask $quoteIdMaskFactory,
        \Auraine\CouponCodes\Model\DataProvider\Collection $ruleCollection,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId,
        \Magento\Framework\ObjectManagerInterface $objectManger
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->ruleCollection = $ruleCollection;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->_objectManager = $objectManger;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        $request = $this->_objectManager->create(\Magento\Framework\App\RequestInterface::class);

        $cartId = $this->getCartId($args);
        $cartId = $this->maskedQuoteIdToQuoteId->execute($cartId);

        $mobileHeader = $request->getHeader(Data::CUSTOM_MOBILE_HEADER_NAME);
        $headerStatus = $mobileHeader == Data::CUSTOM_MOBILE_HEADER_CONTENT;

        $data = $this->ruleCollection->getValidCouponList($headerStatus);

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
