<?php
namespace Auraine\CouponCodes\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    /**
     * Custom header name constant.
     */
    const CUSTOM_MOBILE_HEADER_NAME = "X-REQUESTED-WITH";

    /**
     * Custom header content constant.
     */
    const CUSTOM_MOBILE_HEADER_CONTENT = "Application/Mobile";

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $_collectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManger
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_collectionFactory = $collectionFactory;
        $this->_objectManager = $objectManger;
    }

    /**
     * Get Current Customer group Id.
     *
     * @return int customerGroupId
     */
    public function getCustomerGroupId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getCustomer()->getGroupId();
        } else {
            return 0;
        }
    }

    /**
     * Get Current Website Id.
     *
     * @return int websiteId
     */
    public function getWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
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

        return $this->_collectionFactory->create()
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
        $request = $this->_objectManager->create("Magento\Framework\App\RequestInterface");
        $mobileHeader = $request->getHeader(self::CUSTOM_MOBILE_HEADER_NAME);
        
        return  $mobileHeader == self::CUSTOM_MOBILE_HEADER_CONTENT;
    }
}
