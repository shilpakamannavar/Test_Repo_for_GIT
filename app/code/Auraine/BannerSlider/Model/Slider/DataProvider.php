<?php


namespace Auraine\BannerSlider\Model\Slider;

use Auraine\BannerSlider\Api\Data\SliderInterface;
use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{

    /**
     * @var Load Data
     *
     */
    protected $loadedData;
    
    /**
     * Dataprovider
     *
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param SliderRepositoryInterface $sliderRepository
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        SliderRepositoryInterface $sliderRepository,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->collection = $sliderRepository->getCollection();
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

        /** @var SliderInterface|DataObject $item */
        foreach ($items as $item) {
            $this->loadedData[$item->getEntityId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('bannerslider_slider');
        if (!empty($data)) {
            /** @var SliderInterface|DataObject $slider */
            $slider = $this->collection->getNewEmptyItem();
            $slider->setData($data);
            $this->loadedData[$slider->getEntityId()] = $slider->getData();
            $this->dataPersistor->clear('bannerslider_slider');
        }

        return $this->loadedData;
    }
}
