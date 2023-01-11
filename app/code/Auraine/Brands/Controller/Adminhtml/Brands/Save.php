<?php

namespace Auraine\Brands\Controller\Adminhtml\Brands;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;


class Save extends \Magento\Backend\App\Action
{
    protected $gridFactory;

    protected $_fileUploaderFactory;
 /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Auraine\Brands\Model\GridFactory $gridFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
       
     
        $this->gridFactory = $gridFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        
        $data = $this->getRequest()->getPostValue();
       

        if (isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {
                /*
                * Save image upload
                */
                try {
                    $base_media_path = 'auraine/brands';
                       
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();

            
                    $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem') ->getDirectoryRead(DirectoryList::MEDIA);
                    //     $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            
                    $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath($base_media_path)
                    );
                    $data['image'] = $base_media_path.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }
            

        if (!$data) {
            $this->_redirect('brands/brands/addrow');
            return;
        }
        try {
            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Brand data has been successfully saved.'));
             
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('brands/brands/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Brands::save');
    }
}
