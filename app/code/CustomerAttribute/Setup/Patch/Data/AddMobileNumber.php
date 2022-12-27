<?php
declare(strict_types=1);

namespace Auraine\CustomerAttribute\Setup\Patch\Data;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Eav\Model\Config;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddMobileNumber implements DataPatchInterface
{
    private CustomerSetup $customerSetup;
    private Config $eavConfig;

    /**
     * @param CustomerSetup $customerSetup
     */
    public function __construct(
        CustomerSetup $customerSetup,
        Config $eavConfig
    ) {
        $this->customerSetup = $customerSetup;
        $this->eavConfig = $eavConfig;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->customerSetup->addAttribute(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            \Auraine\CustomerAttribute\Model\Constant::MOBILE_NUMBER,
            [
                'label' => 'Mobile Number',
                'required' => false,
                'user_defined' => 1,
                'system' => 0,
                'input' => 'text',
                'adminhtml_only' => true
            ]
        );

        $this->customerSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            \Auraine\CustomerAttribute\Model\Constant::MOBILE_NUMBER
        );

        $attribute = $this->eavConfig->getAttribute(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            \Auraine\CustomerAttribute\Model\Constant::MOBILE_NUMBER
        );

        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'customer_account_create',
            'customer_account_edit'
        ]);
        $attribute->getResource()->save($attribute);
    }
}
