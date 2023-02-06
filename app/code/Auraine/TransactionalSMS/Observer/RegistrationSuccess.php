<?php
namespace Auraine\TransactionalSMS\Observer;

class RegistrationSuccess implements \Magento\Framework\Event\ObserverInterface
{
    private const CONFIG_PATH = "transaction_sms_control/registration_sms/message";

    /**
     * @var \Auraine\TransactionalSMS\Helper\Data $_helperData
     */
    private $_helperData;

    /**
     *
     * @param \Auraine\TransactionalSMS\Helper\Data $helperData
     */
    public function __construct(
        \Auraine\TransactionalSMS\Helper\Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        $mobileNo = $customer->getCustomAttribute('mobilenumber')->getValue();

        $this->_helperData->customerRegisterSuccessSMS(
            self::CONFIG_PATH,
            $mobileNo,
            $customer->getFirstname().' '.$customer->getLastname()
        );
    }
}
