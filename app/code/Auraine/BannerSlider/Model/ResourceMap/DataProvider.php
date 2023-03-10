<?php

namespace Auraine\BannerSlider\Model\ResourceMap;

use Auraine\BannerSlider\Api\Data\ResourceMapInterface;
use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * hh
     *
     * @var Load Data
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ResourceMapRepositoryInterface $resourceMapRepository
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ResourceMapRepositoryInterface $resourceMapRepository,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->collection = $resourceMapRepository->getCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];

        $items = $this->collection->getItems();

        /** @var ResourceMapInterface|DataObject $item */
        foreach ($items as $item) {
            $this->loadedData[$item->getEntityId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('bannerslider_resource_map');
        if (!empty($data)) {
            /** @var ResourceMapInterface|DataObject $resourceMap */
            $resourceMap = $this->collection->getNewEmptyItem();
            $resourceMap->setData($data);
            $this->loadedData[$resourceMap->getEntityId()] = $resourceMap->getData();
            $this->dataPersistor->clear('bannerslider_resource_map');
        }

        return $this->loadedData;
    }
}
