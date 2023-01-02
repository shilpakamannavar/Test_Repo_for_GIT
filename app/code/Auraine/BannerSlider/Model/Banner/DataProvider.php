<?php

namespace Auraine\BannerSlider\Model\Banner;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Auraine\BannerSlider\Api\Data\BannerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{

    protected $loadedData;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var PoolInterface|null
     */
    private $pool;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param BannerRepositoryInterface $bannerRepository
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        BannerRepositoryInterface $bannerRepository,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->collection = $bannerRepository->getCollection();
        $this->pool = $pool;
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

        /** @var BannerInterface|DataObject $item */
        foreach ($items as $item) {
            $this->loadedData[$item->getEntityId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('bannerslider_banner');
        if (!empty($data)) {
            /** @var BannerInterface|DataObject $banner */
            $banner = $this->collection->getNewEmptyItem();
            $banner->setData($data);
            $this->loadedData[$banner->getEntityId()] = $banner->getData();
            $this->dataPersistor->clear('bannerslider_banner');
        }

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->loadedData = $modifier->modifyData($this->loadedData);
        }

        return $this->loadedData;
    }
}