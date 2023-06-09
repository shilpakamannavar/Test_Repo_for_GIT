<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Resmap;

use Auraine\BannerSlider\Api\ResourceMapRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Auraine_BannerSlider::resource_map';
    /**
     * @var ResourceMapRepositoryInterface
     */
    private $resourceMapRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param ResourceMapRepositoryInterface $resourceMapRepository
     */
    public function __construct(
        Action\Context $context,
        ResourceMapRepositoryInterface $resourceMapRepository
    ) {
        parent::__construct($context);
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
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                $this->resourceMapRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Resource Map with ID %1 deleted successfully', $id));
                $url = $this->getUrl('*/*');
            } catch (CouldNotDeleteException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $url = $this->getUrl('*/*/edit', ['entity_id' => $id]);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $url = $this->getUrl('*/*');
            }
        } else {
            $url = $this->getUrl('*/*');
        }
        return $this->resultRedirectFactory->create()->setUrl($url);
    }
}
