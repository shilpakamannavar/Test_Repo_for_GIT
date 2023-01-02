<?php
declare(strict_types=1);

namespace Auraine\CustomerAttribute\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;

class AdditionalCustomerInfoHandler
{
    private CustomerRepositoryInterface $customerRepository;
    private \Magento\Customer\Api\Data\CustomerExtensionInterfaceFactory $customerExtensionInterfaceFactory;
  

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerExtensionInterfaceFactory $customerExtensionInterfaceFactory
     * @param ReaderCustomerNumberOrder $readerCustomerNumberOrder
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\Data\CustomerExtensionInterfaceFactory $customerExtensionInterfaceFactory,
  
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerExtensionInterfaceFactory = $customerExtensionInterfaceFactory;
    
    }

    /**
     * @param CustomerInterface $customer
     * @return void
     */
    public function addAdditionalInfoExtensionAttribute(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $extensionAttributes = $this->getExtensionAttributes($customer);

        if (!is_null($customer->getCustomAttribute(Constant::MOBILE_NUMBER))) {
            $extensionAttributes->setMobileNumber($customer->getCustomAttribute(Constant::MOBILE_NUMBER)->getValue());
            $customer->setExtensionAttributes($extensionAttributes);
        }
    }

    /**
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Api\Data\CustomerExtensionInterface|null
     */
    public function getExtensionAttributes(CustomerInterface $customer): ?\Magento\Customer\Api\Data\CustomerExtensionInterface
    {
        $extensionAttributes = $customer->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->customerExtensionInterfaceFactory->create();
        }
        return $extensionAttributes;
    }
}
