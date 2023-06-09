<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Resmap;

use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection;

abstract class AbstractMassAction extends Action
{
    public const ADMIN_RESOURCE = 'Auraine_BannerSlider::resource_map';

    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var ResourceMapRepositoryInterface
     */
    protected $resourceMapRepository;

    /**
     * AbstractMassAction constructor.
     *
     * @param Action\Context $context
     * @param ResourceMapRepositoryInterface $resourceMapRepository
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        ResourceMapRepositoryInterface $resourceMapRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->resourceMapRepository = $resourceMapRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $collection = $this->resourceMapRepository->getCollection();

        try {
            $this->filter->getCollection($collection);
            $this->processCollection($collection);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }

    /**
     * Process collection method.
     *
     * @param \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection $collection
     * @return void
     */
    abstract protected function processCollection(Collection $collection): void;
}
