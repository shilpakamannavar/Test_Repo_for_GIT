<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Slider;

use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Auraine\BannerSlider\Model\ResourceModel\Slider\Collection;

abstract class AbstractMassAction extends Action
{
    public const ADMIN_RESOURCE = 'Auraine_BannerSlider::slider';

    /**
     * @var SliderRepositoryInterface
     */
    protected $sliderRepository;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * AbstractMassAction constructor.
     * @param Action\Context $context
     * @param SliderRepositoryInterface $sliderRepository
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        SliderRepositoryInterface $sliderRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->sliderRepository = $sliderRepository;
        $this->filter = $filter;
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
        $collection = $this->sliderRepository->getCollection();
        try {
            $this->filter->getCollection($collection);
            $this->processCollection($collection);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }

    /**
     * Process Collection
     *
     * @param \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection $collection
     * @return void
     */
    abstract protected function processCollection(Collection $collection): void;
}
