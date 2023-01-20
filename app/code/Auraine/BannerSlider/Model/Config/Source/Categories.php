<?php

namespace Auraine\BannerSlider\Model\Config\Source;

class Categories implements \Magento\Framework\Option\ArrayInterface
{

    protected $_categories;
    protected $_storeManager;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collection,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_categories = $collection;
        $this->_storeManager = $storeManager;
    }

    public function toOptionArray()
    {   

    $categories = $this->_categories->create();
    $collection = $categories->addAttributeToSelect('*')->addFieldToFilter('is_active', 1)->setStore($this->_storeManager->getStore());
    $itemArray = array('value' => '', 'label' => '--Please Select--');
        //
        $categoryArray = array();
        $categoryArray[] = $itemArray;
        foreach ($collection as $category)
        {       
            $categoryArray[] = array('value' => $category->getId(), 'label' => $category->getName());

        }
        return $categoryArray;
}
}


?>