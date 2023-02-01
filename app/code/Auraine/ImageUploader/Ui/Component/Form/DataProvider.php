<?php
namespace Auraine\ImageUploader\Ui\Component\Form;

use Magento\Framework\Registry;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Constructor function
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Registry $registry
     * @param \Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory $imageCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        Registry $registry,
        \Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory $imageCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->registry = $registry;
        $this->collection = $imageCollectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [];
    }
}
