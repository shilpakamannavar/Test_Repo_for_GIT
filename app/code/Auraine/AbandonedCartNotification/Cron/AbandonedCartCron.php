<?php
namespace Auraine\AbandonedCartNotification\Cron;

use Exception;
use Auraine\TransactionalSMS\Helper\Data as TransactionHelper;
use Auraine\AbandonedCartNotification\Helper\Data as MailHelper;

class AbandonedCartCron
{

    /**
     * Abandoned cart status
     * @var boolean const
     */
    private const STATUS_CONFIG = 'abandoned_cart_notifications/attributes/abandoned_cart_status';

    /**
     * No. of hours for abandoned cart
     * @var int const
     */
    private const HOURS_CONFIG = 'abandoned_cart_notifications/attributes/hours_for_abandoned_cart';

    /**
     * SMS draft for Abandoned cart
     * @var string const
     */
    private const SMS_CONFIG = 'abandoned_cart_notifications/attributes/message';

    /**
     * @var \Magento\Reports\Model\ResourceModel\Quote\CollectionFactory
     */
    private $_quoteCollectionFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * @var MailHelper
     */
    private $mailhelperData;

    /**
     * @var TransactionHelper
     */
    private $smsData;

    /**
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param MailHelper $mailhelperData
     * @param TransactionHelper $smsData
     */
    public function __construct(
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        MailHelper $mailhelperData,
        TransactionHelper $smsData
    ) {
        $this->_quoteCollectionFactory = $quoteCollectionFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->mailhelperData = $mailhelperData;
        $this->smsData = $smsData;
    }

    /**
     * Cron functionality for sending email and sms on detecting abandoned cart
     *
     * @return void
     */
    public function execute()
    {

        if (!$this->smsData->getConfigValue(self::STATUS_CONFIG)) {
            return;
        }

        $hours = $this->smsData->getConfigValue(self::HOURS_CONFIG);

        $quotes = $this->_quoteCollectionFactory
            ->create()
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('customer_email', ['neq' => null])
            ->addFieldToFilter('customer_id', ['neq' => null])
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter(
                [
                    'abandoned_cart_email_sent', 'abandoned_cart_sms_sent'
                ],
                [
                    ['eq' => 0],
                    ['eq' => 0]
                ]
            );

        foreach ($quotes as $quote) {

            $customer = null;
            try {
                $customer = $this->customerRepositoryInterface->getById($quote->getCustomerId());
            } catch (Exception $e) {
                continue;
            }

            $hourdiff = $this->calculateTimeDiff($quote->getData('updated_at'));
    
            if ($hourdiff >= $hours && $customer !== null) {

                $itemCount = $quote->getData('items_count');

                if ($quote->getData('abandoned_cart_email_sent') == 0) {
                    if ($this->sendEmail($customer, $itemCount, $quote)) {
                        // Set email flag on quoute.
                        $quote->setAbandonedCartEmailSent(1);
                    }
                }

                if ($quote->getData('abandoned_cart_sms_sent') == 0) {
                    if ($this->sendSMS($customer, $itemCount)) {
                        // Set sms flag on quoute.
                        $quote->setAbandonedCartSmsSent(1);
                    }
                }
                $quote->save();
            }
        }
    }

    /**
     * Calculate time difference between quote updated at with current time.
     *
     * @param string $updatedAt
     * @return float
     */
    private function calculateTimeDiff($updatedAt)
    {
        $updatedAtTimeStamp = strtotime($updatedAt);
        $current = strtotime(date('Y-m-d H:i:s'));

        return abs(round(($current - $updatedAtTimeStamp)/3600, 1));
    }

    /**
     * Send email functionality
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param int $itemCount
     * @param \Magento\Quote\Model\Quote $quote
     * @return boolean
     */
    private function sendEmail($customer, $itemCount, $quote)
    {
        $items = [
            'name' => $customer->getFirstname(). ' '. $customer->getLastname(),
            'itemCount' => $itemCount,
            'storeId' => $quote->getData('store_id')
        ];

        $this->mailhelperData->sendMail($quote->getData('customer_email'), $items);

        return true;
    }

    /**
     * Send email functionality
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param int $itemCount
     * @return boolean
     */
    private function sendSMS($customer, $itemCount)
    {
        $mobileNo = $customer->getCustomAttribute('mobilenumber')->getValue();
        $smsContent = $this->smsData->generateMessage(
            ['{{item_count}}'],
            [$itemCount],
            self::SMS_CONFIG
        );

        $this->smsData->dispachSMS($smsContent, $mobileNo);

        return true;
    }
}
