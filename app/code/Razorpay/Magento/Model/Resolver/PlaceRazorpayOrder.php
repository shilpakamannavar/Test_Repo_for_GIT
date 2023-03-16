<?php
declare (strict_types = 1);

namespace Razorpay\Magento\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Magento\Quote\Api\CartManagementInterface;
use Razorpay\Magento\Model\PaymentMethod;

class PlaceRazorpayOrder implements ResolverInterface
{

    protected $scopeConfig;

    protected $cartManagement;

    protected $objectManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        GetCartForUser $getCartForUser,
        \Magento\Quote\Api\CartManagementInterface $cartManagement,
        PaymentMethod $paymentMethod
    ) {
        $this->scopeConfig    = $scopeConfig;
        $this->getCartForUser = $getCartForUser;
        $this->cartManagement = $cartManagement;
        $this->rzp = $paymentMethod->rzp;
        $this->objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * @param GetCartForUser $getCartForUser
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        } try {

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();

        $maskedCartId = $args['cart_id'];

        $cart            = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);
        $receiptId      = $cart->getId();
        $amount          = (int) (number_format($cart->getGrandTotal() * 100, 0, ".", ""));
        $paymentAction  = $this->scopeConfig->getValue('payment/razorpay/payment_action', $storeScope);
        $paymentCapture = ($paymentAction === 'authorize') ? 0 : 1;
        $order = $this->rzp->order->create([
            'amount'          => $amount,
            'receipt'         => $receiptId,
            'currency'        => $cart->getQuoteCurrencyCode(),
            'payment_capture' => $paymentCapture,
            'app_offer'       => (($cart->getBaseSubtotal() - $cart->getBaseSubtotalWithDiscount()) > 0) ? 1 : 0,
        ]);

        if (null !== $order && !empty($order->id)) {

            $responseContent = [
                'success'        => true,
                'rzp_order_id'   => $order->id,
                'order_quote_id' => $receiptId,
                'amount'         => number_format((float) $cart->getGrandTotal(), 2, ".", ""),
                'currency'       => $cart->getQuoteCurrencyCode(),
                'message'        => 'Razorpay Order created successfully'
            ];


            //save to razorpay orderLink
            $orderLinkCollection = $this->objectManager
                ->get('Razorpay\Magento\Model\OrderLink')
                ->getCollection()
                ->addFilter('quote_id', $receiptId)
                ->getFirstItem();

            $orderLinkData = $orderLinkCollection->getData();

            if (empty($orderLinkData['entity_id']) === false) {

                $orderLinkCollection->setRzpOrderId($order->id)
                    ->setRzpOrderAmount($amount)
                    ->save();
            } else {
                $orderLink = $this->objectManager->create('Razorpay\Magento\Model\OrderLink');
                $orderLink->setQuoteId($receiptId)
                    ->setRzpOrderId($order->id)
                    ->setRzpOrderAmount($amount)
                    ->save();
            }

            $result = $responseContent;

        }else {
            $result = [
                'success' => false,
                'message' => "Razorpay Order not generated. Something went wrong",
            ];
        }
    } catch (\Razorpay\Api\Errors\Error $e) {
        $result = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }  catch (\Exception $e) {

        $result = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
        return $result;
    }
}
