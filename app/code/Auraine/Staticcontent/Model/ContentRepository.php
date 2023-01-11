<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Model;

use Auraine\Staticcontent\Api\ContentRepositoryInterface;
use Auraine\Staticcontent\Api\Data\ContentInterface;
use Auraine\Staticcontent\Api\Data\ContentInterfaceFactory;
use Auraine\Staticcontent\Api\Data\ContentSearchResultsInterfaceFactory;
use Auraine\Staticcontent\Model\ResourceModel\Content as ResourceContent;
use Auraine\Staticcontent\Model\ResourceModel\Content\CollectionFactory as ContentCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ContentRepository implements ContentRepositoryInterface
{

    /**
     * @var Content
     */
    protected $searchResultsFactory;

    /**
     * @var ContentCollectionFactory
     */
    protected $contentCollectionFactory;

    /**
     * @var ResourceContent
     */
    protected $resource;

    /**
     * @var ContentInterfaceFactory
     */
    protected $contentFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceContent $resource
     * @param ContentInterfaceFactory $contentFactory
     * @param ContentCollectionFactory $contentCollectionFactory
     * @param ContentSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceContent $resource,
        ContentInterfaceFactory $contentFactory,
        ContentCollectionFactory $contentCollectionFactory,
        ContentSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->contentFactory = $contentFactory;
        $this->contentCollectionFactory = $contentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ContentInterface $content)
    {
        try {
            $this->resource->save($content);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the content: %1',
                $exception->getMessage()
            ));
        }
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function get($contentId)
    {
        $content = $this->contentFactory->create();
        $this->resource->load($content, $contentId);
        if (!$content->getId()) {
            throw new NoSuchEntityException(__('Content with id "%1" does not exist.', $contentId));
        }
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->contentCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ContentInterface $content)
    {
        try {
            $contentModel = $this->contentFactory->create();
            $this->resource->load($contentModel, $content->getContentId());
            $this->resource->delete($contentModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Content: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($contentId)
    {
        return $this->delete($this->get($contentId));
    }
}

