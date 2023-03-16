<?php
namespace Auraine\CsvUploader\Controller\Adminhtml\Csv;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Validation\ValidationException;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Auraine\CsvUploader\Console\Command\ImportHex;
use Auraine\CsvUploader\Console\Command\ImportOptions;
use Auraine\CsvUploader\Console\Command\ImportSwatches;

class Save extends \Magento\Backend\App\Action
{

    const REDIRECT_URL = '*/*/upload';

  /**
   *
   * @var UploaderFactory
   */
    protected $uploaderFactory;

  /**
   * @var Filesystem\Directory\WriteInterface
   */
    protected $mediaDirectory;

    /**
     * @var ImportHex
     */
    protected $hexColor;

    /**
     * @var ImportOptions
     */
    protected $importOptions;

    /**
     * @var ImportSwatches
     */
    protected $importSwatches;

    /**
     * Function Construct
     *
     * @param Context $context
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param ImportHex $hexColor
     */
    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        ImportHex $hexColor,
        ImportOptions $importOptions,
        ImportSwatches $importSwatches
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->hexColor = $hexColor;
        $this->importOptions = $importOptions;
        $this->importSwatches = $importSwatches;
    }

    /**
     * Function Execute
     *
     * @return void
     */
    public function execute()
    {
        try {
            if ($this->getRequest()
                    ->getMethod() !== 'POST' ||
                    !$this->_formKeyValidator->validate(
                        $this->getRequest()
                    )) {
                throw new LocalizedException(__('Invalid Request'));
            }

        //validate csv
            $fileUploader = null;
            $params = $this->getRequest()->getParams();
            try {
                  $imageId = 'image';
                if (isset($params['image']) && count($params['image'])) {
                    $imageId = $params['image'][0];
                    if (!file_exists($imageId['tmp_name'])) {
                          $imageId['tmp_name'] = $imageId['path'] . '/' . $imageId['file'];
                    }
                }
                $fileUploader = $this->uploaderFactory->create(['fileId' => $imageId]);
                $fileUploader->setAllowedExtensions(['csv']);
                $fileUploader->setAllowRenameFiles(true);
                $fileUploader->setAllowCreateFolders(true);
                $fileUploader->validateFile();
        //upload csv
                $info = $fileUploader->save($this->mediaDirectory->getAbsolutePath('csvUploader/csv'));
                $csvPath = "pub/media/csvUploader/csv/".$info['file'];
                if ($params['name'] == 'hex color') {
                    $attributeCode = "color";
                    $this->hexColor->importFromCsvFile($csvPath, $attributeCode);
                } elseif ($params['name'] == 'swatches color') {
                    $attributeCode = "color";
                    $this->importSwatches->importFromCsvFile($csvPath, $attributeCode);
                } elseif ($params['name'] == 'size') {
                    $attributeCode = "size";
                    $this->importOptions->importFromCsvFile($csvPath, $attributeCode);
                } elseif ($params['name'] == 'color') {
                    $attributeCode = "color";
                    $this->importOptions->importFromCsvFile($csvPath, $attributeCode);
                }

            } catch (ValidationException $e) {
                throw new LocalizedException(
                    __(
                        'CSV extension is not supported. Only extensions allowed are CSV'
                    )
                );
            } catch (\Exception $e) {
                //if an except is thrown, no image has been uploaded
                throw new LocalizedException(__('CSV is required'));
            }

            $this->messageManager->addSuccessMessage(__('CSV uploaded successfully'));

            return $this->_redirect(self::REDIRECT_URL);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect(self::REDIRECT_URL);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An error occurred, please try again later.'));
            return $this->_redirect(self::REDIRECT_URL);
        }
    }

    /**Is allowes
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_CsvUploader::csv_save');
    }
}
