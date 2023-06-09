<?php

namespace Razorpay\Magento\Observer;

use Razorpay\Api\Api;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Payment;
use Razorpay\Magento\Model\PaymentMethod;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\RequestInterface;
use Razorpay\Magento\Model\Config;


/**
 * Class AfterConfigSaveObserver
 * @package Razorpay\Magento\Observer
 */
class AfterConfigSaveObserver implements ObserverInterface
{

    /**
     * Store key
     */
    const STORE = 'store';


    private $request;
    private $configWriter;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    protected $keyId;

    protected $keySecret;

    protected $rzp;

    protected $webhookUrl;

    protected $webhookId;

    /**
     * StatusAssignObserver constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        \Razorpay\Magento\Model\Config $config,
        RequestInterface $request,
        WriterInterface $configWriter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->configWriter = $configWriter;
        $this->storeManager = $storeManager;
        $this->logger          = $logger;

        $this->config = $config;

        $this->keyId = $this->config->getConfigData(Config::KEY_PUBLIC_KEY);
        $this->keySecret = $this->config->getConfigData(Config::KEY_PRIVATE_KEY);

        $this->rzp = new Api($this->keyId, $this->keySecret);

        $this->webhookUrl = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_WEB
            ) . 'razorpay/payment/webhook';

        $this->webhookId = null;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {

        $razorpayParams = $this->request->getParam('groups')['razorpay']['fields'];

        if (in_array($_SERVER['SERVER_ADDR'], ["127.0.0.1","::1"])) {
            $this->config->setConfigData('enable_webhook', 0);

            $this->logger->info("Can't enable/disable webhook on localhost.");
            return;
        }

        try {
            $this->getExistingWebhook();

            if (empty($razorpayParams['enable_webhook']['value']) === true) {
                $this->disableWebhook();
                return;
            }

            $events = [];

            foreach ($razorpayParams['webhook_events']['value'] as $event) {
                $events[$event] = true;
            }

            if (empty($this->webhookId) === false) {
                $this->rzp->webhook->edit([
                    "url" => $this->webhookUrl,
                    "events" => $events,
                    "secret" => $razorpayParams['webhook_secret']['value'],
                    "active" => true,
                ], $this->webhookId);

                $this->logger->info("Razorpay Webhook Updated by Admin.");
            } else {
                $this->rzp->webhook->create([
                    "url" => $this->webhookUrl,
                    "events" => $events,
                    "secret" => $razorpayParams['webhook_secret']['value'],
                    "active" => true,
                ]);

                $this->logger->info("Razorpay Webhook Created by Admin");
            }

        } catch (\Razorpay\Api\Errors\Error $e) {
            $this->logger->info($e->getMessage());
            //in case of error disable the webhook config
            $this->disableWebhook();
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());

            $this->disableWebhook();
        }
    }

    /**
     * @param string $url
     *
     * @return return array
     */
    private function getExistingWebhook()
    {

        try {
            //fetch all the webhooks
            $webhooks = $this->rzp->webhook->all();

            if (($webhooks->count) > 0 && (empty($this->webhookUrl) === false)) {
                foreach ($webhooks->items as $webhook) {
                    if ($webhook->url === $this->webhookUrl) {
                        $this->webhookId = $webhook->id;
                        return ['id' => $webhook->id];
                    }
                }
            }
        } catch (\Razorpay\Api\Errors\Error $e) {
            $this->logger->info($e->getMessage());
            $this->disableWebhook();
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            $this->disableWebhook();
        }

        return ['id' => null];
    }

    private function disableWebhook()
    {
        $this->config->setConfigData('enable_webhook', 0);

        try {
            $this->rzp->webhook->edit([
                "url" => $this->webhookUrl,
                "active" => false,
            ], $this->webhookId);

            $this->logger->info("Razorpay Webhook Disabled by Admin.");
        } catch (\Razorpay\Api\Errors\Error $e) {
            $this->logger->info($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }

        $this->logger->info("Webhook disabled.");
    }

}
