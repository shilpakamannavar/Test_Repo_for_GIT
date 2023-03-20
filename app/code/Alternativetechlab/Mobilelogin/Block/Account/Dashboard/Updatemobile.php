<?php
namespace Alternativetechlab\Mobilelogin\Block\Account\Dashboard;

use Magento\Framework\View\Element\Template\Context;

class Updatemobile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customersession;

    protected $helper;

    /**
     * Updatemobile constructor.
     * @param Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Alternativetechlab\Mobilelogin\Helper\Data $helper
    ) {
        $this->customersession = $customerSession;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return int|mixed
     */
    public function getCustomerid()
    {
        $customerId = 0;
        if ($this->customersession->isLoggedIn()) {
            $customerId = $this->customersession->getCustomer()->getId();
        }

        return $customerId;
    }

    /**
     * @return int
     */
    public function getMobilenumber()
    {
        $mobileNumber = 0;
        if ($this->customersession->isLoggedIn()) {
            $mobileNumber = $this->customersession->getCustomer()->getMobilenumber();
        }

        return $mobileNumber;
    }

    public function getGeoCountryCode()
    {
        return $this->helper->getGeoCountryCode();
    }

    public function getApplicableCountryJson()
    {
        return $this->helper->getApplicableCountry(false);
    }
}
