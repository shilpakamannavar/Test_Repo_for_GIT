<?php

namespace Auraine\BannerSlider\Model\Config\Source;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $_categories;

    /**
     * @var array
     */
    protected $_storeManager;

     /**
      * Category constructor.
      *
      * @param string $collection
      * @param string $storeManager
      */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collection,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_categories = $collection;
        $this->_storeManager = $storeManager;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {

        $categories = $this->_categories->create();
        $collection = $categories
                     ->addAttributeToSelect('*')
                     ->addFieldToFilter('is_active', 1)
                     ->setStore($this->_storeManager
                     ->getStore());
        $itemArray = ['value' => '', 'label' => '--Please Select--'];
        //
        $categoryArray = [];
        $categoryArray[] = $itemArray;
        foreach ($collection as $category) {
            $categoryArray[] = ['value' => $category->getId(), 'label' => $category->getName()];

        }
        return $categoryArray;
    }
}
