<?php
namespace Auraine\ImageUploader\Controller\Adminhtml\Images;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Auraine\ImageUploader\Model\ImageFactory;
use Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory ;

class Delete extends Action
{
    /**
     * Imagefactory
     *
     */
    public $imageFactory;

     /**
     * @var CollectionFactory
     */
    private $imageCollectionFactory;
    
    /**
     * Constructer function
     *
     * @param Context $context
     * @param ImageFactory $imageFactory
     * @param CollectionFactory $imageCollectionFactory
     */
    public function __construct(
        Context $context,
        ImageFactory $imageFactory,
        CollectionFactory $imageCollectionFactory,
       ) {
        parent::__construct($context);
        $this->imageFactory = $imageFactory;
        $this->imageCollectionFactory = $imageCollectionFactory;
    }

    /**
     * function
     *
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        $imageCollection = $this->imageCollectionFactory->create();
        $imageCollection->addFieldToSelect('path')->addFieldToFilter('image_id', $id);
        $image = $this->imageFactory->create()->load($id);
        $url = $imageCollection->getData();
        try {
            $image->delete();
            @unlink(getcwd() . '/media/' . $url[0]['path']);
            $this->messageManager->addSuccessMessage(__('You deleted the image.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
