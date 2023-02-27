<?php
namespace Auraine\ExtendedReminder\Plugin;

/**
 * Reminder Rule data model
 *
 * @method string getName()
 * @method \Magento\Reminder\Model\Rule setName(string $value)
 * @method string getDescription()
 * @method \Magento\Reminder\Model\Rule setDescription(string $value)
 * @method string getConditionsSerialized()
 * @method \Magento\Reminder\Model\Rule setConditionsSerialized(string $value)
 * @method string getConditionSql()
 * @method \Magento\Reminder\Model\Rule setConditionSql(string $value)
 * @method int getIsActive()
 * @method \Magento\Reminder\Model\Rule setIsActive(int $value)
 * @method int getSalesruleId()
 * @method \Magento\Reminder\Model\Rule setSalesruleId(int $value)
 * @method string getSchedule()
 * @method \Magento\Reminder\Model\Rule setSchedule(string $value)
 * @method string getDefaultLabel()
 * @method \Magento\Reminder\Model\Rule setDefaultLabel(string $value)
 * @method string getDefaultDescription()
 * @method \Magento\Reminder\Model\Rule setDefaultDescription(string $value)
 * @method \Magento\Reminder\Model\Rule setActiveFrom(string $value)
 * @method \Magento\Reminder\Model\Rule setActiveTo(string $value)
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExtendedRule extends \Magento\Rule\Model\AbstractModel
{
    /**
     * Email template path
     * @var string const
     */
    private const XML_PATH_EMAIL_TEMPLATE = 'magento_reminder_email_template';

    /**
     * Abandoned cart sms path
     * @var string const
     */
    private const ABANDONED_CART_SMS = 'abandoned_cart/reminder_sms/message';

    /**
     * Store template data defined per store view, will be used in email templates as variables
     *
     * @var array
     */
    protected $storeData = [];

    /**
     * Reminder data
     *
     * @var \Magento\Reminder\Helper\Data
     */
    protected $reminderData = null;

    /**
     * @var \Magento\Quote\Model\QueryResolver
     */
    protected $queryResolver;

    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $objectManager;

    /**
     * Rule constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Reminder\Helper\Data $reminderData
     * @param \Magento\Reminder\Model\ResourceModel\Rule $resource
     * @param \Magento\Quote\Model\QueryResolver $queryResolver
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Reminder\Helper\Data $reminderData,
        \Magento\Reminder\Model\ResourceModel\Rule $resource,
        \Magento\Quote\Model\QueryResolver $queryResolver,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->reminderData = $reminderData;
        $this->queryResolver = $queryResolver;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(\Magento\Reminder\Model\ResourceModel\Rule::class);
    }

    /**
     * Set template, label and description data per store
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();

        $storeData = $this->_getResource()->getStoreData($this->getId());
        $defaultTemplate = self::XML_PATH_EMAIL_TEMPLATE;

        foreach ($storeData as $data) {
            $template = empty($data['template_id']) ? $defaultTemplate : $data['template_id'];
            $this->setData('store_template_' . $data['store_id'], $template);
            $this->setData('store_label_' . $data['store_id'], $data['label']);
            $this->setData('store_description_' . $data['store_id'], $data['description']);
        }

        return $this;
    }

    /**
     * Set aggregated conditions SQL and reset sales rule Id if applicable
     *
     * @return $this
     */
    public function beforeSave()
    {
        $this->setConditionSql($this->getConditions()->getConditionsSql(null, new \Zend_Db_Expr(':website_id')));

        if (!$this->getSalesruleId()) {
            $this->setSalesruleId(null);
        }

        parent::beforeSave();
        return $this;
    }

    /**
     * Getter for rule combine conditions instance
     *
     * @return \Magento\Reminder\Model\Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        $rootFactory = $this->objectManager->create(\Magento\Reminder\Model\Rule\Condition\Combine\RootFactory::class);

        return $rootFactory->create();
    }

    /**
     * Getter for rule actions collection instance
     *
     * @return \Magento\Rule\Model\Action\Collection
     */
    public function getActionsInstance()
    {
        $collectionFactory = $this->objectManager->create(\Magento\Rule\Model\Action\CollectionFactory::class);

        return $collectionFactory->create();
    }

    /**
     * Send reminder emails
     *
     * @param \Magento\Reminder\Model\Rule $rule
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function aroundSendReminderEmails(\Magento\Reminder\Model\Rule $rule)
    {
        $inlineTranslation = $this->objectManager->create(\Magento\Framework\Translate\Inline\StateInterface::class);
        $inlineTranslation->suspend();

        $identity = $this->reminderData->getEmailIdentity();

        $this->_matchCustomers();
        $limit = $this->reminderData->getOneRunLimit();

        $recipients = $this->_getResource()->getCustomersForNotification($limit, $this->getRuleId());

        foreach ($recipients as $recipient) {
            /* @var $customer \Magento\Customer\Model\Customer */
            $customerFactory = $this->objectManager->create(\Magento\Customer\Model\CustomerFactory::class);

            $customer = $customerFactory->create()->load($recipient['customer_id']);
            if (!$customer || !$customer->getId()) {
                continue;
            }

            if ($customer->getStoreId()) {
                $store = $customer->getStore();
            } else {
                $storeManager = $this->objectManager->create(\Magento\Store\Model\StoreManagerInterface::class);

                $store = $storeManager->getWebsite($customer->getWebsiteId())->getDefaultStore();
            }

            $storeData = $this->getStoreData($recipient['rule_id'], $store->getId());
            if (!$storeData) {
                continue;
            }

            /* @var $coupon \Magento\SalesRule\Model\Coupon */
            $couponFactory = $this->objectManager->create(\Magento\SalesRule\Model\CouponFactory::class);
            $coupon = $couponFactory->create()->load($recipient['coupon_id']);

            $templateVars = [
                'store' => $store,
                'coupon' => $coupon,
                'customer' => $customer,
                'customer_data' => [
                    'name' => $customer->getName(),
                ],
                'promotion_name' => $storeData['label'] ?: $this->getDefaultLabel(),
                'promotion_description' => $storeData['description'] ?: $this->getDefaultDescription(),
            ];

            $transportBuilder = $this->objectManager->create(\Magento\SalesRule\Model\CouponFactory::class);
            $transport = $transportBuilder->setTemplateIdentifier(
                $storeData['template_id']
            )->setTemplateOptions(
                ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store->getId()]
            )->setTemplateVars(
                $templateVars
            )->setFrom(
                $identity
            )->addTo(
                $customer->getEmail()
            )->getTransport();

            try {
                $transport->sendMessage();

                $mobileNo = $customer->getCustomAttribute('mobilenumber')->getValue();

                if ($mobileNo) {
                    $helperData = $this->objectManager->create(\Auraine\TransactionalSMS\Helper\Data::class);

                    $helperData->customerAbandonedCartSMS(
                        self::ABANDONED_CART_SMS,
                        $mobileNo,
                        $customer->getFirstname().' '.$customer->getLastname()
                    );
                }

                $this->_getResource()->addNotificationLog($recipient['rule_id'], $customer->getId());
            } catch (\Magento\Framework\Exception\MailException $e) {
                $this->_getResource()->updateFailedEmailsCounter($recipient['rule_id'], $customer->getId());
            }
        }

        $inlineTranslation->resume();

        return $this;
    }

    /**
     * Match customers for current rule and assign coupons
     *
     * @return $this
     */
    protected function _matchCustomers()
    {
        $dateFactory = $this->objectManager->create(\Magento\Framework\Stdlib\DateTime\DateTimeFactory::class);

        $threshold = $this->reminderData->getSendFailureThreshold();
        $currentDate = $dateFactory->create()->date('Y-m-d H:i:s');
        $rules = $this->getCollection()->addDateFilter($currentDate)->addIsActiveFilter(1);
        if ($this->getRuleId()) {
            $rules->addRuleFilter($this->getRuleId());
        }

        foreach ($rules as $rule) {
            $this->_getResource()->deactivateMatchedCustomers($rule->getId());

            if ($rule->getSalesruleId()) {
                /* @var $salesRule \Magento\SalesRule\Model\Rule */
                $salesRuleObj = $this->objectManager->create(\Magento\SalesRule\Model\Rule::class);
                $salesRule = $salesRuleObj->load($rule->getSalesruleId());
                $websiteIds = array_intersect($rule->getWebsiteIds(), $salesRule->getWebsiteIds());
            } else {
                $salesRule = null;
                $websiteIds = $rule->getWebsiteIds();
            }

            if ($this->queryResolver->isSingleQuery()) {
                foreach ($websiteIds as $websiteId) {
                    $this->_getResource()->saveMatchedCustomers($rule, $salesRule, $websiteId, $threshold);
                }
            } else {
                $this->processCondition($rule, $salesRule, $websiteIds, $threshold);
            }
        }
        return $this;
    }

    /**
     * Process rule condition
     *
     * @param \Magento\Reminder\Model\Rule $rule
     * @param \Magento\SalesRule\Model\Rule $salesRule
     * @param array $websiteIds
     * @param int|null $threshold
     * @return $this
     */
    protected function processCondition($rule, $salesRule, array $websiteIds, $threshold = null)
    {
        $rule->afterLoad();
        $relatedCustomers = [];
        foreach ($websiteIds as $websiteId) {
            //get customers ids that satisfy conditions
            $customers = $rule->getConditions()->getSatisfiedIds($websiteId, $rule->getId(), $threshold);
            foreach ($customers as $customer) {
                $relatedCustomers[] = [
                    'entity_id' => $customer['entity_id'],
                    'coupon_id' => $customer['coupon_id'],
                    'website_id' => $websiteId,
                ];
            }
        }
        $this->_getResource()->saveSatisfiedCustomers($relatedCustomers, $rule, $salesRule);
        return $this;
    }

    /**
     * Retrieve store template data
     *
     * @param int $ruleId
     * @param int $storeId
     * @return array|false
     */
    public function getStoreData($ruleId, $storeId)
    {
        if (!isset($this->storeData[$ruleId][$storeId])) {
            if ($data = $this->_getResource()->getStoreTemplateData($ruleId, $storeId)) {
                if (empty($data['template_id'])) {
                    $data['template_id'] = self::XML_PATH_EMAIL_TEMPLATE;
                }
                $this->storeData[$ruleId][$storeId] = $data;
            } else {
                return false;
            }
        }

        return $this->storeData[$ruleId][$storeId];
    }

    /**
     * Detaches Sales Rule from all Email Remainder Rules that uses it
     *
     * @param int $salesRuleId
     * @return $this
     */
    public function detachSalesRule($salesRuleId)
    {
        $this->getResource()->detachSalesRule($salesRuleId);
        return $this;
    }

    /**
     * Retrieve active from date.
     *
     * Implemented for backwards compatibility with old property called "active_from"
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getActiveFrom()
    {
        return $this->getData('from_date');
    }

    /**
     * Retrieve active to date.
     *
     * Implemented for backwards compatibility with old property called "active_to"
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getActiveTo()
    {
        return $this->getData('to_date');
    }
}
