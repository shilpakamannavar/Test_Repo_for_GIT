<?php

namespace Alternativetechlab\Mobilelogin\Controller\Adminhtml\Index;

use Alternativetechlab\Mobilelogin\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Update extends Action
{
    protected $customerRepoInterface;
    protected $customerFactory;
    public function __construct(
        Context $context,
        Data $helper,
        JsonFactory $resultJsonFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepoInterface,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->customerRepoInterface = $customerRepoInterface;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $customerCollectoin = $this->customerFactory->create()->getCollection()
            ->addAttributeToSelect("*")
            ->load();
        foreach ($customerCollectoin as $customer) {
            if ($customer->getPrimaryBillingAddress()) {
                if ($customer->getPrimaryBillingAddress()->getTelephone()) {
                    $telephone = $customer->getPrimaryBillingAddress()->getTelephone();
                    $customerObj = $this->customerRepoInterface->getById($customer->getId());
                    $customerObj->setCustomAttribute('mobilenumber', $telephone);
                    $this->customerRepoInterface->save($customerObj);
                } else {
                    $customerObj = $this->customerRepoInterface->getById($customer->getId());
                    $customerObj->setCustomAttribute('mobilenumber', null);
                    $this->customerRepoInterface->save($customerObj);
                }
            }
        }
        return $result->setData(count($customerCollectoin));
    }
    protected function _isAllowed()
    {
        return true;
    }
}
