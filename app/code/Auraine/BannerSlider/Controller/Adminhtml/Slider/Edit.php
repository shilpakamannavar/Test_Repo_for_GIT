<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Slider;


use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Auraine_BannerSlider::slider';
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        Action\Context $context,
        SliderRepositoryInterface $sliderRepository
    )
    {
        parent::__construct($context);
        $this->sliderRepository = $sliderRepository;
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
        try {
            /** @var Page $page */
            $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $slider = $this->sliderRepository->loadById($id);
                $page->getConfig()->getTitle()->set(__('Edit Slider "%1" (%2)', $slider->getTitle(), $slider->getEntityId()));
            } else {
                $page->getConfig()->getTitle()->set(__('Create New Slider'));
            }
            return $page;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The slider you\'re looking for does not exist'));
            return $this->resultRedirectFactory->create()->setPath('*/*');
        }
    }
}