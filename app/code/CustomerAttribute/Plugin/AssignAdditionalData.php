<?php
declare(strict_types=1);

namespace Auraine\CustomerAttribute\Plugin;

use Auraine\CustomerAttribute\Model\AdditionalCustomerInfoHandler;

class AssignAdditionalData
{
    private \Auraine\CustomerAttribute\Model\AdditionalCustomerInfoHandler $additionalCustomerInfoHandler;

    /**
     * @param AdditionalCustomerInfoHandler $additionalCustomerInfoHandler
     */
    public function __construct(
        \Auraine\CustomerAttribute\Model\AdditionalCustomerInfoHandler $additionalCustomerInfoHandler
    ) {
        $this->additionalCustomerInfoHandler = $additionalCustomerInfoHandler;
    }

    public function afterGet(
        \Magento\Customer\Api\CustomerRepositoryInterface $subject,
        \Magento\Customer\Api\Data\CustomerInterface $customer
    ) {
        $this->addAdditionalInfoExtensionAttribute($customer);
        return $customer;
    }

    public function afterGetList(
        \Magento\Customer\Api\CustomerRepositoryInterface $subject,
        \Magento\Customer\Api\Data\CustomerSearchResultsInterface $results
    ) {
        array_map([$this, 'addAdditionalInfoExtensionAttribute'], $results->getItems());
        return $results;
    }

    private function addAdditionalInfoExtensionAttribute(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $this->additionalCustomerInfoHandler->addAdditionalInfoExtensionAttribute($customer);
    }
}
