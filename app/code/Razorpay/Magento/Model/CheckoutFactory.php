<?php

namespace Razorpay\Magento\Model;

class CheckoutFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param string $className
     * @param array $data
     * @return \Razorpay\Magento\Model\PaymentMethod
     */
    public function create($className, array $data = [])
    {
        return $this->objectManager->create($className, $data);
    }
}
