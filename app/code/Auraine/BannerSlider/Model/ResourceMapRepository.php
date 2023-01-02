<?php

namespace Auraine\BannerSlider\Model;


use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Auraine\BannerSlider\Api\Data\ResourceMapInterfaceFactory as ModelFactory;
use Auraine\BannerSlider\Model\ResourceModel\ResourceMap as ResourceModel;
use Auraine\BannerSlider\Model\ResourceModel\ResourceMap\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Auraine\BannerSlider\Api\Data\ResourceMapSearchResultInterfaceFactory;
use Psr\Log\LoggerInterface;

class ResourceMapRepository implements ResourceMapRepositoryInterface
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
     * @var \Auraine\BannerSlider\Api\Data\ResourceMapInterface[]
     */
    protected $objectCache;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ResourceMapSearchResultInterfaceFactory
     */
    private $resourceMapSearchResultFactory;

    /**
     * ResourceMapRepository constructor.
     * @param ModelFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ResourceMapSearchResultInterfaceFactory $resourceMapSearchResultFactory
     * @param LoggerInterface $logger
     * @param array $objectCache
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ResourceMapSearchResultInterfaceFactory $resourceMapSearchResultFactory,
        LoggerInterface $logger,
        array $objectCache = []
    )
    {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceMapSearchResultFactory = $resourceMapSearchResultFactory;
        $this->logger = $logger;
        $this->objectCache = $objectCache;
    }

    /**
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\ResourceMapInterface
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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\ResourceMapInterface
    {
        return $this->modelFactory->create();
    }

    /**
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap): \Auraine\BannerSlider\Api\Data\ResourceMapInterface
    {
        try {
            $this->resourceModel->save($resourceMap);
            return $this->loadById($resourceMap->getEntityId(), false);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('There was some error saving the resource map'));
        }
    }

    /**
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\ResourceMapInterface $resourceMap): bool
    {
        try {
            $this->resourceModel->delete($resourceMap);
            $this->cacheObject('id', $resourceMap->getEntityId(), null);
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotDeleteException(__('There was some eror deleting the resource map'));
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
     * @return \Auraine\BannerSlider\Api\Data\ResourceMapSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCollection();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var \Auraine\BannerSlider\Api\Data\ResourceMapSearchResultInterface $searchResult */
        $searchResult = $this->resourceMapSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria)
            ->setTotalCount($collection->getSize())
            ->setItems($collection->getItems());
        foreach ($searchResult->getItems() as $item) {
            $this->cacheObject('id', $item->getEntityId(), $item);
        }
        return $searchResult;
    }

    /**
     * @return \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection
    {
        return $this->collectionFactory->create();
    }

    /**
     * @param string $type
     * @param string $identifier
     * @param \Auraine\BannerSlider\Api\Data\ResourceMapInterface|null $object
     */
    protected function cacheObject($type, $identifier, $object)
    {
        $cacheKey = $this->getCacheKey($type, $identifier);
        $this->objectCache[$cacheKey] = $object;
    }

    /**
     * @param string $type
     * @param string $identifier
     * @return bool|\Auraine\BannerSlider\Api\Data\ResourceMapInterface
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