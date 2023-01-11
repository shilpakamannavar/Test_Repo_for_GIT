<?php
namespace Auraine\Brands\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class BrandsList
{
    protected $_brandsFactory;

    protected $_objectManager;

    public function __construct(
        \Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
        )
    {
        $this->_brandsFactory  = $brandsFactory;
        $this->_objectManager = $objectManager;
    }
 
    public function getBrandsList($filter_entity_id,$filter_label, $filter_url)
    {
        $brandData = [];
        try {
            $collection = $this->_brandsFactory->create()->addFieldToFilter('status',1);
            $brandData = $collection->getData();
       
             if($filter_entity_id){
              
                $collection = $this->_brandsFactory->create()->addFieldToFilter('entity_id',$filter_entity_id);
                $brandData = $collection->getData();
            }
            if($filter_label)
            {
                $brandData =   $this->filter_function($filter_label);
            }
            if($filter_url)
            {
                $collection = $this->_brandsFactory->create()->addFieldToFilter('url_key',$filter_url);
                $brandData = $collection->getData();
            }

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $brandData;
    } 
    public function save($data)
    {
      
       if(is_array($data)) {
            $training = $this->_objectManager->create('Auraine\Brands\Model\Grid');
            $training->setData($data)->save();
       }
       
	return ['message' => 'Successfully a new Brand has been created'];
    }


    //****** this function is used to filter the grands by their feature ********** */
    public function filter_function($filter_label)
    {
        if($filter_label)
        {
            $collection = $this->_brandsFactory->create()->addFieldToFilter($filter_label,1);
            $brandData = $collection->getData();
            return $brandData ;
        }
    }
}