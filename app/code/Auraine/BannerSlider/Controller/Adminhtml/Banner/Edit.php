<?php


namespace Auraine\BannerSlider\Controller\Adminhtml\Banner;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Auraine_BannerSlider::banner';
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param BannerRepositoryInterface $bannerRepository
     */
    public function __construct(
        Action\Context $context,
        BannerRepositoryInterface $bannerRepository
    )
    {
        parent::__construct($context);
        $this->bannerRepository = $bannerRepository;
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
                $banner = $this->bannerRepository->loadById($id);
                $page->getConfig()->getTitle()->set(__('Edit Banner "%1" (%2)', $banner->getTitle(), $banner->getEntityId()));
            } else {
                $page->getConfig()->getTitle()->set(__('Create New Banner'));
            }
            return $page;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The banner you\'re looking for does not exist'));
            return $this->resultRedirectFactory->create()->setPath('*/*');
        }
    }
}