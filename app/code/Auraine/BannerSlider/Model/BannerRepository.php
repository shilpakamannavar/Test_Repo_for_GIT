<?php

namespace Auraine\BannerSlider\Model;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Auraine\BannerSlider\Api\Data\BannerInterfaceFactory as ModelFactory;
use Auraine\BannerSlider\Model\ResourceModel\Banner as ResourceModel;
use Auraine\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Auraine\BannerSlider\Api\Data\BannerSearchResultInterfaceFactory;
use Psr\Log\LoggerInterface;
use Auraine\BannerSlider\Api\Data\BannerInterface;

class BannerRepository implements BannerRepositoryInterface
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
     * @var \Auraine\BannerSlider\Api\Data\BannerInterface[]
     */
    protected $objectCache;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var BannerSearchResultInterfaceFactory
     */
    private $bannerSearchResultFactory;

    /**
     * BannerRepository constructor.
     * @param ModelFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param BannerSearchResultInterfaceFactory $bannerSearchResultFactory
     * @param LoggerInterface $logger
     * @param array $objectCache
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        BannerSearchResultInterfaceFactory $bannerSearchResultFactory,
        LoggerInterface $logger,
        array $objectCache = []
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->bannerSearchResultFactory = $bannerSearchResultFactory;
        $this->logger = $logger;
        $this->objectCache = $objectCache;
    }

    /**
     * Load BY Id
     *
     * @param int $id
     * @param bool $loadFromCache
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadById(int $id, bool $loadFromCache = true): \Auraine\BannerSlider\Api\Data\BannerInterface
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
     * Create Factory
     *
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     */
    public function create(): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        return $this->modelFactory->create();
    }

    /**
     * Save
     *
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface $banner
     * @return \Auraine\BannerSlider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(BannerInterface $banner): \Auraine\BannerSlider\Api\Data\BannerInterface
    {
        try {
            $this->resourceModel->save($banner);
            return $this->loadById($banner->getEntityId(), false);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('There was some error saving the banner'));
        }
    }

    /**
     * Delete Banner
     *
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface $banner
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Auraine\BannerSlider\Api\Data\BannerInterface $banner): bool
    {
        try {
            $this->resourceModel->delete($banner);
            $this->cacheObject('id', $banner->getEntityId(), null);
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotDeleteException(__('There was some eror deleting the banner'));
        }
    }

    /**
     * Delete By Id
     *
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
     * Get List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Auraine\BannerSlider\Api\Data\BannerSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCollection();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var \Auraine\BannerSlider\Api\Data\BannerSearchResultInterface $searchResult */
        $searchResult = $this->bannerSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria)
            ->setTotalCount($collection->getSize())
            ->setItems($collection->getItems());
        foreach ($searchResult->getItems() as $item) {
            $this->cacheObject('id', $item->getEntityId(), $item);
        }
        return $searchResult;
    }

    /**
     * Create Factory
     *
     * @return \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection
     */
    public function getCollection(): \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection
    {
        return $this->collectionFactory->create();
    }

    /**
     * Cache Object
     *
     * @param string $type
     * @param string $identifier
     * @param \Auraine\BannerSlider\Api\Data\BannerInterface|null $object
     */
    protected function cacheObject($type, $identifier, $object)
    {
        $cacheKey = $this->getCacheKey($type, $identifier);
        $this->objectCache[$cacheKey] = $object;
    }

    /**
     * Get Cache object
     *
     * @param string $type
     * @param string $identifier
     * @return bool|\Auraine\BannerSlider\Api\Data\BannerInterface
     */
    protected function getCachedObject($type, $identifier)
    {
        $cacheKey = $this->getCacheKey($type, $identifier);
        return $this->objectCache[$cacheKey] ?? false;
    }

    /**
     * Get Cache Key
     *
     * @param string $type
     * @param string $identifier
     * @return bool|\Auraine\BannerSlider\Api\Data\BannerInterface
     */
    protected function getCacheKey($type, $identifier)
    {
        return $type . '_' . $identifier;
    }
}
