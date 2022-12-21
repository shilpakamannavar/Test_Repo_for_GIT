<?php
namespace Auraine\CouponCodes\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
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
}
