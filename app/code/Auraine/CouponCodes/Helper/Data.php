<?php
namespace Auraine\CouponCodes\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    /**
     * Custom header name constant.
     */
    public const CUSTOM_MOBILE_HEADER_NAME = "X-REQUESTED-WITH";

    /**
     * Custom header content constant.
     */
    public const CUSTOM_MOBILE_HEADER_CONTENT = "Application/Mobile";

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManger
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManger;
    }

    /**
     * Get Current Customer group Id.
     *
     * @return int customerGroupId
     */
    private function getCustomerGroupId()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getGroupId();
        } else {
            return 0;
        }
    }

    /**
     * Get Current Website Id.
     *
     * @return int websiteId
     */
    private function getWebsiteId()
    {
        return $this->storeManager->getStore()->getWebsiteId();
    }

    /**
     * Gets the cart pricing rule applied to the given coupon code.
     *
     * @return \Magento\SalesRule\Model\ResourceModel\Rule\Collection
     */
    public function getCurrentCouponRule()
    {
        $websiteId = $this->getWebsiteId();
        $customerGroupId = $this->getCustomerGroupId();

        return $this->collectionFactory->create()
            ->addWebsiteGroupDateFilter($websiteId, $customerGroupId)
            ->addAllowedSalesRulesFilter()
            ->addFieldToFilter('coupon_type', ['neq' => '1'])
            ->addFieldToFilter('is_visible_in_list', ['eq' => '1']);
    }

    /**
     * Validates the request is from app or from web.
     *
     * @return boolean
     */
    public function getMobileHeaderStatus()
    {
        $request = $this->objectManager->create(\Magento\Framework\App\RequestInterface::class);
        $mobileHeader = $request->getHeader(self::CUSTOM_MOBILE_HEADER_NAME);
        
        return  $mobileHeader == self::CUSTOM_MOBILE_HEADER_CONTENT;
    }
}
