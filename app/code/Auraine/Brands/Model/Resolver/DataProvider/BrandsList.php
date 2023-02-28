<?php
namespace Auraine\Brands\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class BrandsList
{
     /**
      * @var brandsFactory
      */
    protected $brandsFactory;
    /** use for obj managaer
     *
     * @var objectManager
     */
    protected $objectManager;
   /**
    * Brand factory
    *
    * @param brandsFactory $brandsFactory
    *
    * Obj Manager
    * @param objectManager $objectManager
    *
    * Constructor
    * @return string $construct
    */
    public function __construct(
        \Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->brandsFactory  = $brandsFactory;
        $this->objectManager = $objectManager;
    }
        /**
         * Mapped Brand List page.
         *
         * @param filter_entity_id $filterEntityId ,filter_label $filterLabel ,filter_url $filterUrl
         *
         * @return \Magento\Backend\Model\View\Result\Page
         */
    public function getBrandsList($filterEntityId, $filterLabel, $filterUrl)
    {
        $brandData = [];
        try {
            $collection = $this->brandsFactory->create()->addFieldToFilter('status', 1);
            $brandData = $collection->getData();
       
            if ($filterEntityId) {
              
                $collection = $this->brandsFactory->create()->addFieldToFilter('entity_id', $filterEntityId);
                $brandData = $collection->getData();
            }
            if ($filterLabel) {
                $brandData =   $this->filterFunction($filterLabel);
            }
            if ($filterUrl) {
                $collection = $this->brandsFactory->create()->addFieldToFilter('url_key', $filterUrl);
                $brandData = $collection->getData();
            }

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $brandData;
    }
     /**
      * Mapped Brand Save.
      *
      * @param String $data
      *
      * @return \Magento\Backend\Model\View\Result\Page
      */
    public function save($data)
    {
        if (is_array($data)) {
            $training = $this->objectManager->create('Auraine\Brands\Model\Grid');
            $training->setData($data)->save();
        }
        return ['message' => 'Successfully a new Brand has been created'];
    }
     /**
      * This function is used to filter the grands by their feature
      *
      * @param String $filterLabel
      *
      * @return \Magento\Backend\Model\View\Result\Page
      */
    public function filterFunction($filterLabel)
    {
        if ($filterLabel) {
            $collection = $this->brandsFactory->create()->addFieldToFilter($filterLabel, 1);
            return $collection->getData();
        }
    }
    /**
     * Get brand name form brand id
     *
     * @param int $id
     * @return string
     */
    public function getBrandFromId($id)
    {
        $brandData = null ;
        try {
            $collection = $this->brandsFactory->create()->addFieldToSelect('title')->addFieldToFilter('entity_id', $id);
            $brandData = $collection->getData();
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $brandData[0]['title'];
    }
}
