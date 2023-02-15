<?php

declare(strict_types=1);

namespace Auraine\PushNotification\Model;

use Auraine\PushNotification\Api\Data\TemplateInterface;
use Auraine\PushNotification\Api\Data\TemplateInterfaceFactory;
use Auraine\PushNotification\Api\Data\TemplateSearchResultsInterfaceFactory;
use Auraine\PushNotification\Api\TemplateRepositoryInterface;
use Auraine\PushNotification\Model\ResourceModel\Template as ResourceTemplate;
use Auraine\PushNotification\Model\ResourceModel\Template\CollectionFactory as TemplateCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class TemplateRepository implements TemplateRepositoryInterface
{

    /**
     * @var Template
     */
    protected $searchResultsFactory;

    /**
     * @var TemplateInterfaceFactory
     */
    protected $templateFactory;

    /**
     * @var TemplateCollectionFactory
     */
    protected $templateCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ResourceTemplate
     */
    protected $resource;


    /**
     * @param ResourceTemplate $resource
     * @param TemplateInterfaceFactory $templateFactory
     * @param TemplateCollectionFactory $templateCollectionFactory
     * @param TemplateSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceTemplate $resource,
        TemplateInterfaceFactory $templateFactory,
        TemplateCollectionFactory $templateCollectionFactory,
        TemplateSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->templateFactory = $templateFactory;
        $this->templateCollectionFactory = $templateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(TemplateInterface $template)
    {
        try {
            $this->resource->save($template);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the template: %1',
                $exception->getMessage()
            ));
        }
        return $template;
    }

    /**
     * @inheritDoc
     */
    public function get($templateId)
    {
        $template = $this->templateFactory->create();
        $this->resource->load($template, $templateId);
        if (!$template->getId()) {
            throw new NoSuchEntityException(__('Template with id "%1" does not exist.', $templateId));
        }
        return $template;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->templateCollectionFactory->create();
        
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
    public function delete(TemplateInterface $template)
    {
        try {
            $templateModel = $this->templateFactory->create();
            $this->resource->load($templateModel, $template->getTemplateId());
            $this->resource->delete($templateModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Template: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($templateId)
    {
        return $this->delete($this->get($templateId));
    }
}

