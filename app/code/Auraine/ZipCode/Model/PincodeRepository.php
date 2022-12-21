<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model;

use Auraine\ZipCode\Api\Data\PincodeInterface;
use Auraine\ZipCode\Api\Data\PincodeInterfaceFactory;
use Auraine\ZipCode\Api\Data\PincodeSearchResultsInterfaceFactory;
use Auraine\ZipCode\Api\PincodeRepositoryInterface;
use Auraine\ZipCode\Model\ResourceModel\Pincode as ResourcePincode;
use Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory as PincodeCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class PincodeRepository implements PincodeRepositoryInterface
{

    /**
     * @var Pincode
     */
    protected $searchResultsFactory;

    /**
     * @var ResourcePincode
     */
    protected $resource;

    /**
     * @var PincodeCollectionFactory
     */
    protected $pincodeCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var PincodeInterfaceFactory
     */
    protected $pincodeFactory;

    /**
     * @param ResourcePincode $resource
     * @param PincodeInterfaceFactory $pincodeFactory
     * @param PincodeCollectionFactory $pincodeCollectionFactory
     * @param PincodeSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourcePincode $resource,
        PincodeInterfaceFactory $pincodeFactory,
        PincodeCollectionFactory $pincodeCollectionFactory,
        PincodeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->pincodeFactory = $pincodeFactory;
        $this->pincodeCollectionFactory = $pincodeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(PincodeInterface $pincode)
    {
        try {
            $this->resource->save($pincode);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the pincode: %1',
                $exception->getMessage()
            ));
        }
        return $pincode;
    }

    /**
     * @inheritDoc
     */
    public function get($pincodeId)
    {
        $pincode = $this->pincodeFactory->create();
        $this->resource->load($pincode, $pincodeId);
        if (!$pincode->getId()) {
            throw new NoSuchEntityException(__('pincode with id "%1" does not exist.', $pincodeId));
        }
        return $pincode;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->pincodeCollectionFactory->create();
        
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
    public function delete(PincodeInterface $pincode)
    {
        try {
            $pincodeModel = $this->pincodeFactory->create();
            $this->resource->load($pincodeModel, $pincode->getPincodeId());
            $this->resource->delete($pincodeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the pincode: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($pincodeId)
    {
        return $this->delete($this->get($pincodeId));
    }
}

