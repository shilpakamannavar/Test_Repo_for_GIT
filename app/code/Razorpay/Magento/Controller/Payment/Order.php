<?php

namespace Razorpay\Magento\Controller\Payment;

use Razorpay\Api\Api;
use Razorpay\Magento\Model\PaymentMethod;
use Magento\Framework\Controller\ResultFactory;

class Order extends \Razorpay\Magento\Controller\BaseController
{
    protected $quote;

    protected $checkoutSession;

    protected $cartManagement;

    protected $cache;

    protected $orderRepository;

    protected $config;
    protected $objectManagement;

    protected $logger;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Razorpay\Model\Config\Payment $razorpayConfig
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Razorpay\Magento\Model\Config $config,
        \Magento\Quote\Api\CartManagementInterface $cartManagement,
        \Razorpay\Magento\Model\CheckoutFactory $checkoutFactory,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $config
        );

        $this->config          = $config;
        $this->cartManagement  = $cartManagement;
        $this->customerSession = $customerSession;
        $this->checkoutFactory = $checkoutFactory;
        $this->cache = $cache;
        $this->orderRepository = $orderRepository;
        $this->logger          = $logger;

        $this->objectManagement   = \Magento\Framework\App\ObjectManager::getInstance();
    }

    private function getSalesOrderData($receiptId)
    {
        # fetch the related sales order and verify the payment ID with rzp payment id
        # To avoid duplicate order entry for same quote
        $collection = $this->_objectManager->get('Magento\Sales\Model\Order')
            ->getCollection()
            ->addFieldToSelect('entity_id')
            ->addFilter('quote_id', $receiptId)
            ->getFirstItem();

        return $collection->getData();
    }

    private function getMazeVersion()
    {
        return $this->_objectManager->get(
            'Magento\Framework\App\ProductMetadataInterface'
        )->getVersion();
    }

    private function getModuleVersion()
    {
        return $this->_objectManager->get(
            'Magento\Framework\Module\ModuleList'
        )->getOne('Razorpay_Magento')['setup_version'];
    }

    public function execute()
    {
        $receiptId = $this->getQuote()->getId();

        if (empty($_POST['error']) === false) {
            $this->messageManager->addError(__('Payment Failed'));
            return $this->_redirect('checkout/cart');
        }

        if (isset($_POST['order_check'])) {
            if (empty($this->cache->load("quote_processing_".$receiptId)) === false) {
                $responseContent = [
                    'success'   => true,
                    'order_id'  => false,
                    'parameters' => []
                ];

                $salesOrder = $this->getSalesOrderData($receiptId);

                if (empty($salesOrder['entity_id']) === false) {

                    $this->logger->info("Razorpay inside order already processed with webhook quoteID:" . $receiptId
                        ." and OrderID:".$salesOrder['entity_id']);

                    $this->checkoutSession
                        ->setLastQuoteId($this->getQuote()->getId())
                        ->setLastSuccessQuoteId($this->getQuote()->getId())
                        ->clearHelperData();

                    $order = $this->orderRepository->get($salesOrder['entity_id']);

                    if ($order) {
                        $this->checkoutSession->setLastOrderId($order->getId())
                            ->setLastRealOrderId($order->getIncrementId())
                            ->setLastOrderStatus($order->getStatus());
                    }

                    $responseContent['order_id'] = true;
                }
            } else {
                if (empty($receiptId) === false) {
                    //set the chache to stop webhook processing
                    $this->cache->save("started", "quote_Front_processing_$receiptId", ["razorpay"], 30);

                    $this->logger->info("Razorpay front-end order processing started quoteID:" . $receiptId);

                    $responseContent = [
                        'success'   => false,
                        'parameters' => []
                    ];
                } else {
                    $this->logger->info("Razorpay order already processed with quoteID:" . $this->checkoutSession
                            ->getLastQuoteId());

                    $responseContent = [
                        'success'    => true,
                        'order_id'   => true,
                        'parameters' => []
                    ];

                }
            }

            $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $response->setData($responseContent);
            $response->setHttpResponseCode(200);

            return $response;
        }

        if (isset($_POST['razorpay_payment_id'])) {
            $this->getQuote()->getPayment()->setMethod(PaymentMethod::METHOD_CODE);

            try {
                if (!$this->customerSession->isLoggedIn()) {
                    $this->getQuote()->setCheckoutMethod($this->cartManagement::METHOD_GUEST);
                    $this->getQuote()->setCustomerEmail($this->customerSession->getCustomerEmailAddress());
                }
                $this->cartManagement->placeOrder($this->getQuote()->getId());
                return $this->_redirect('checkout/onepage/success');
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $this->_redirect('checkout/cart');
            }
        } else {
            if (empty($_POST['email']) === true) {
                $this->logger->info("Email field is required");

                $responseContent = [
                    'message'   => "Email field is required",
                    'parameters' => []
                ];

                $code = 200;
            } else {
                $amount = (int) (number_format($this->getQuote()->getGrandTotal() * 100, 0, ".", ""));

                $paymentAction = $this->config->getPaymentAction();

                $mazeVersion = $this->getMazeVersion();
                $moduleVersion =  $this->getModuleVersion();
                $this->customerSession->setCustomerEmailAddress($_POST['email']);

                $paymentCapture = ($paymentAction === 'authorize') ? 0 : 1;
                $code = 400;

                try {
                    $order = $this->rzp->order->create([
                        'amount' => $amount,
                        'receipt' => $receiptId,
                        'currency' => $this->getQuote()->getQuoteCurrencyCode(),
                        'paymentCapture' => $paymentCapture,
                        'app_offer' => ($this->getDiscount() > 0) ? 1 : 0
                    ]);

                    $responseContent = [
                        'message'   => 'Unable to create your order. Please contact support.',
                        'parameters' => []
                    ];

                    if (null !== $order && !empty($order->id)) {

                        $merchantPreferences    = $this->getMerchantPreferences();

                        $responseContent = [
                            'success'           => true,
                            'rzp_order'         => $order->id,
                            'order_id'          => $receiptId,
                            'amount'            => $order->amount,
                            'quote_currency'    => $this->getQuote()->getQuoteCurrencyCode(),
                            'quote_amount'      => number_format($this->getQuote()->getGrandTotal(), 2, ".", ""),
                            'mazeVersion'      => $mazeVersion,
                            'moduleVersion'    => $moduleVersion,
                            'is_hosted'         => $merchantPreferences['is_hosted'],
                            'image'             => $merchantPreferences['image'],
                            'embedded_url'      => $merchantPreferences['embedded_url'],
                        ];

                        $code = 200;

                        $this->checkoutSession->setRazorpayOrderID($order->id);
                        $this->checkoutSession->setRazorpayOrderAmount($amount);

                        //save to razorpay orderLink
                        $orderLinkCollection = $this->_objectManager->get('Razorpay\Magento\Model\OrderLink')
                            ->getCollection()
                            ->addFilter('quote_id', $receiptId)
                            ->getFirstItem();

                        $orderLinkData = $orderLinkCollection->getData();

                        if (empty($orderLinkData['entity_id']) === false) {
                            $orderLinkCollection->setRzpOrderId($order->id)
                                ->save();
                        } else {
                            $orderLnik = $this->_objectManager->create('Razorpay\Magento\Model\OrderLink');
                            $orderLnik->setQuoteId($receiptId)
                                ->setRzpOrderId($order->id)
                                ->save();
                        }

                    }
                } catch (\Razorpay\Api\Errors\Error $e) {
                    $responseContent = [
                        'message'   => $e->getMessage(),
                        'parameters' => []
                    ];
                } catch (\Exception $e) {
                    $responseContent = [
                        'message'   => $e->getMessage(),
                        'parameters' => []
                    ];
                }
            }

            //set the chache for race with webhook
            $this->cache->save("started", "quote_Front_processing_$receiptId", ["razorpay"], 300);

            $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $response->setData($responseContent);
            $response->setHttpResponseCode($code);
            return $response;
        }
    }

    public function getOrderID()
    {
        return $this->checkoutSession->getRazorpayOrderID();
    }

    public function getRazorpayOrderAmount()
    {
        return $this->checkoutSession->getRazorpayOrderAmount();
    }

    protected function getMerchantPreferences()
    {
        try {
            $api = new Api($this->config->getKeyId(), "");

            $response = $api->request->request("GET", "preferences");
        }catch (\Razorpay\Api\Errors\Error $e) {
            echo 'Magento Error : ' . $e->getMessage();
        }

        $preferences = [];

        $preferences['embedded_url'] = Api::getFullUrl("checkout/embedded");
        $preferences['is_hosted'] = false;
        $preferences['image'] = $response['options']['image'];

        if (isset($response['options']['redirect']) && $response['options']['redirect'] === true) {
            $preferences['is_hosted'] = true;
        }

        return $preferences;
    }

    public function getDiscount()
    {
        return ($this->getQuote()->getBaseSubtotal() - $this->getQuote()->getBaseSubtotalWithDiscount());
    }
}
