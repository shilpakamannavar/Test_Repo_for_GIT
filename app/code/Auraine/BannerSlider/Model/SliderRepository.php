<?php

namespace Auraine\BannerSlider\Model;


use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Auraine\BannerSlider\Api\Data\SliderInterfaceFactory as ModelFactory;
use Auraine\BannerSlider\Model\ResourceModel\Slider as ResourceModel;
use Auraine\BannerSlider\Model\ResourceModel\Slider\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Auraine\BannerSlider\Api\Data\SliderSearchResultInterfaceFactory;
use Psr\Log\LoggerInterface;

class SliderRepository implements SliderRepositoryInterface
{

    /**
     * @var ModelFactory
     */
    private $modelFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var \Auraine\BannerSlider\Api\Data\SliderInterface[]
     */
    protected $objectCache;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SliderSearchResultInterfaceFactory
     */
    private $sliderSearchResultFactory;

    /**
     * SliderRepository constructor.
     * @param ModelFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SliderSearchResultInterfaceFactory $sliderSearchResultFactory
     * @param LoggerInterface $logger
     * @param array $objectCache
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SliderSearchResultInterfaceFactory $sliderSearchResultFactory,
        LoggerInterface $logger,
        array $objectCache = []
    )
    {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->sliderSearchResultFactory = $sliderSearchResultFactory;
        $this->logger = $logger;
        $this->objectCache = $objectCache;
    }

    /**
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\SliderInterface
    {
        $cachedObject = $this->getCachedObject('id', $id);
        if ($loadFromCache && $cachedObject) {
            return $cachedObject;
        } else {
            $model = $this->create();
            $this->resourceModel->load($model, $id);
            if (!$model->getEntityId()) {
                throw NoSuchEntityException::singleField('entity_id', $id);
            }
            $this->cacheObject('id', $id, $model);
            return $model;
        }
    }

    /**
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\SliderInterface
    {
        return $this->modelFactory->create();
    }

    /**
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\SliderInterface $slider): \Auraine\BannerSlider\Api\Data\SliderInterface
    {
        try {
            $this->resourceModel->save($slider);
            return $this->loadById($slider->getEntityId(), false);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('There was some error saving the slider'));
        }
    }

    /**
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface $slider
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\SliderInterface $slider): bool
    {
        try {
            $this->resourceModel->delete($slider);
            $this->cacheObject('id', $slider->getEntityId(), null);
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotDeleteException(__('There was some eror deleting the slider'));
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->loadById($id));
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\SliderSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCollection();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var \Auraine\BannerSlider\Api\Data\SliderSearchResultInterface $searchResult */
        $searchResult = $this->sliderSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria)
            ->setTotalCount($collection->getSize())
            ->setItems($collection->getItems());
        foreach ($searchResult->getItems() as $item) {
            $this->cacheObject('id', $item->getEntityId(), $item);
        }
        return $searchResult;
    }

    /**
     * @return \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection
    {
        return $this->collectionFactory->create();
    }

    /**
     * @param string $type
     * @param string $identifier
     * @param \Auraine\BannerSlider\Api\Data\SliderInterface|null $object
     */
    protected function cacheObject($type, $identifier, $object)
    {
        $cacheKey = $this->getCacheKey($type, $identifier);
        $this->objectCache[$cacheKey] = $object;
    }

    /**
     * @param string $type
     * @param string $identifier
     * @return bool|\Auraine\BannerSlider\Api\Data\SliderInterface
     */
    protected function getCachedObject($type, $identifier)
    {
        $cacheKey = $this->getCacheKey($type, $identifier);
        return $this->objectCache[$cacheKey] ?? false;
    }

    protected function getCacheKey($type, $identifier)
    {
        return $type . '_' . $identifier;
    }
}