<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Brands\Model\Product\Attribute\Source;

class BrandAttrProd extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function __construct(
        \Auraine\Brands\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
        )
    {
        $this->_brandsFactory  = $brandsFactory;
        $this->_objectManager = $objectManager;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $collection = $this->_brandsFactory->create()->addFieldToSelect('entity_id','value')->addFieldToSelect('title','label')->addFieldToFilter('status','1');
        $brandData = $collection->getData();
        $dummy= [
            ['value' => '', 'label' => 'Select Brand'],
            ];
        return array_merge($dummy ,$brandData) ;
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
        $attributeCode => [
        'unsigned' => false,
        'default' => null,
        'extra' => null,
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'nullable' => true,
        'comment' => $attributeCode . ' column',
        ],
        ];
    }

    /**
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = [];

        $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = ['type' => 'index', 'fields' => [$this->getAttribute()->getAttributeCode()]];

        return $indexes;
    }

    /**
     * @param int $store
     * @return \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        return $this->eavAttrEntity->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }

    /**
     * @param int $id
     * @return brandData|array
     */
    public function getAllBrandDataById($id)
    {
        $collection = $this->_brandsFactory->create()->addFieldToSelect('entity_id','value')->addFieldToSelect('title','label')->addFieldToFilter('entity_id',$id);
        $brandData = $collection->getData();
        return  $brandData;

    }
}

