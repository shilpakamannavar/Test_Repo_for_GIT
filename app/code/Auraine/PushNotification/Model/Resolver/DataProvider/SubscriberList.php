<?php
namespace Auraine\PushNotification\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Auraine\PushNotification\Model\ResourceModel\Subscriber\CollectionFactory;

class SubscriberList
{
     /**
      * @var _subscriberFactory
      */
    protected $_subscriberFactory;
    /** use for obj managaer
     *
     * @var _objectManager
     */
    protected $_objectManager;
   /**
    * Subscriber factory
    *
    * @param subscriberFactory $subscriberFactory
    *
    * Obj Manager
    * @param objectManager $objectManager
    *
    * Constructor
    * @return string $construct
    */
    public function __construct(
        \Auraine\PushNotification\Model\ResourceModel\Subscriber\CollectionFactory $subscriberFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_subscriberFactory  = $subscriberFactory;
        $this->_objectManager = $objectManager;
    }
        /**
         * Mapped subscriber List page.
         *
         * @param $device_type
         *
         * @return \Magento\Backend\Model\View\Result\Page
         */
    public function getSubscriberList($device_type, $device_token, $gender)
    {
      
        $subscriberData = [];
        try {
            $collection = $this->_subscriberFactory->create();
            $subscriberData = $collection->getData();
            if ($device_type) {              
                $collection = $this->_subscriberFactory->create()->addFieldToFilter('device_type', $device_type);
                $subscriberData = $collection->getData();               
            } elseif ($device_token) {              
                $collection = $this->_subscriberFactory->create()->addFieldToFilter('token', $device_token);
                $subscriberData = $collection->getData();               
            } elseif ($gender) {              
                $collection = $this->_subscriberFactory->create()->addFieldToFilter('gender', $gender);
                $subscriberData = $collection->getData();               
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $subscriberData;
    }
    
}
