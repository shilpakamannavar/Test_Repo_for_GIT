<?php

namespace Auraine\CustomGraphql\Model\Resolver\DataProvider;

class Getproductslist
{
    protected $_objectManager;

    private $productDataProvider;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Deferred\Product $productDataProvider
        )
    {
        $this->_objectManager = $objectManager;
        $this->productDataProvider = $productDataProvider;
    }
    
    /**
     * @params string $product_to_show
     * this function return all the product lists by a attribute type and category id
     **/
    public function getProductsByAttribute( string $attribute_type, int $category_id): array
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productCollection = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

            $collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter($attribute_type, '1')
            ->load();

            $collection->getSelect()->join(
                ['thirdTable' => $collection->getTable('catalog_product_entity_varchar')], //2nd table name by which you want to join
                'e.entity_id= `thirdTable`.entity_id AND thirdTable.attribute_id = (select attribute_id from eav_attribute where attribute_code = \'name\' and entity_type_id = 4)', // common column which available in both table
                ['product_name' => 'thirdTable.value','product_id' => 'e.entity_id']// '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
            )->join(
                ['mycategoryTable' => $collection->getTable('catalog_category_product')], 
                'e.entity_id= `mycategoryTable`.product_id AND mycategoryTable.category_id = "'.$category_id.'"', 
                ['category_id' => 'mycategoryTable.category_id']
            )->join(
                ['categoryTable' => $collection->getTable('catalog_category_entity_varchar')],
                'mycategoryTable.category_id= `categoryTable`.entity_id AND categoryTable.attribute_id = (select attribute_id from eav_attribute where attribute_code = \'name\' and entity_type_id = 3)', // common column which available in both table
                ['category_name' => 'categoryTable.value']
            )->join(
                ['priceTable' => $collection->getTable('catalog_product_entity_decimal')],
                'e.entity_id= `priceTable`.entity_id AND priceTable.attribute_id= (select attribute_id from eav_attribute where attribute_code = \'price\' and entity_type_id = 4) ', // common column which available in both table
                ['product_price' => 'priceTable.value']
            )->join(
                ['descriptionTable' => $collection->getTable('catalog_product_entity_text')],
                'e.entity_id= `descriptionTable`.entity_id AND descriptionTable.attribute_id= (select attribute_id from eav_attribute where attribute_code = \'short_description\' and entity_type_id = 4) ', // common column which available in both table
                ['short_description' => 'descriptionTable.value']
            )->join(
                ['imageTable' => $collection->getTable('catalog_product_entity_varchar')],
                'e.entity_id= `imageTable`.entity_id AND imageTable.attribute_id= (select attribute_id from eav_attribute where attribute_code = \'image\' and entity_type_id = 4) ', // common column which available in both table
                ['image' => 'imageTable.value']
            )->join(
                ['thumbnailTable' => $collection->getTable('catalog_product_entity_varchar')],
                'e.entity_id= `thumbnailTable`.entity_id AND thumbnailTable.attribute_id= (select attribute_id from eav_attribute where attribute_code = \'thumbnail\' and entity_type_id = 4) ', // common column which available in both table
                ['thumbnail' => 'thumbnailTable.value']
            )->join(
                ['smallimageTable' => $collection->getTable('catalog_product_entity_varchar')],
                'e.entity_id= `smallimageTable`.entity_id AND smallimageTable.attribute_id= (select attribute_id from eav_attribute where attribute_code = \'small_image\' and entity_type_id = 4) ', // common column which available in both table
                ['small_image' => 'smallimageTable.value']
            )   ;

            $productlistData = $collection->getData();
        } catch (NoSuchEntityException $e) {
                 throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
            }
        return $productlistData;
    }//End of function getProductsByAttribute
}
