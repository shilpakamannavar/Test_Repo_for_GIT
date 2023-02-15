<?php
namespace Auraine\PushNotification\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Auraine\PushNotification\Model\ResourceModel\Template\CollectionFactory;

class TemplateList
{
     /**
      * @var _templateFactory
      */
    protected $_templateFactory;
    /** use for obj managaer
     *
     * @var _objectManager
     */
    protected $_objectManager;
   /**
    * Template factory
    *
    * @param templateFactory $templateFactory
    *
    * Obj Manager
    * @param objectManager $objectManager
    *
    * Constructor
    * @return string $construct
    */
    public function __construct(
        \Auraine\PushNotification\Model\ResourceModel\Template\CollectionFactory $templateFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_templateFactory  = $templateFactory;
        $this->_objectManager = $objectManager;
    }
        /**
         * Mapped template List page.
         *
         * @param $template_id
         *
         * @return \Magento\Backend\Model\View\Result\Page
         */
    public function getTemplateList($template_id)
    {
      
        $templateData = [];
        try {
            $collection = $this->_templateFactory->create();
            $templateData = $collection->getData();
            if ($template_id) {
              
                $collection = $this->_templateFactory->create()->addFieldToFilter('template_id', $template_id);
                $templateData = $collection->getData();
               
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $templateData;
    }
    
}
