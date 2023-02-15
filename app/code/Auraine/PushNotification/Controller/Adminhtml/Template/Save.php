<?php
declare(strict_types=1);

namespace Auraine\PushNotification\Controller\Adminhtml\Template;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;



class Save extends \Magento\Backend\App\Action
{
   
    protected $dataPersistor;
  /**
     * @var Filesystem
     */
     private $filesystem; /**
     * @var UploaderFactory
     */
     private $uploaderFactory;
     
      /**
   * @var Filesystem\Directory\WriteInterface
   */
    protected $mediaDirectory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
         Action\Context $context,
         Filesystem $filesystem,
         UploaderFactory $uploaderFactory
         ) {
         parent::__construct($context);
         $this->filesystem = $filesystem;
         $this->uploaderFactory = $uploaderFactory;
         $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
         
         }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $id = $this->getRequest()->getParam('template_id');
        
        $model = $this->_objectManager->create(\Auraine\PushNotification\Model\Template::class)->load($id);
        $model->setData($data);
       
        try {
       
       $model->setData($data);
            if (isset($data['id'])) {
                $model->setId($data['id']);
            }
            $model->save();
            $this->messageManager->addSuccess(__('data has been successfully saved.'));
             
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('auraine_pushnotification/template/index/');
    }
    
}
