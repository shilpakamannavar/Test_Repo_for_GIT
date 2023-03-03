<?php

namespace Auraine\Zipcode\Controller\Adminhtml\Index;

use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\Result\JsonFactory;
use Auraine\ZipCode\Model\ResourceModel\Pincode\CollectionFactory;
use Magento\Framework\Filesystem\DriverInterface;

class Export extends \Magento\Backend\App\Action
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;
     /**
      * @var DirectoryList
      */
    protected $directoryList;
     /**
      * @var CollectionFactory
      */
    protected $entityFactory;
     /**
      * @var Filter
      */
    protected $filter;
    /**
     * @var DriverInterface
     */
      protected $fileDriver;
    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param DirectoryList $directoryList
     * @param CollectionFactory $entityFactory
     * @param Filter $filter
     * @param DriverInterface $fileDriver
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        DirectoryList $directoryList,
        CollectionFactory $entityFactory,
        Filter $filter,
        DriverInterface $fileDriver
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->entityFactory = $entityFactory;
        $this->filter = $filter;
        $this->fileDriver = $fileDriver;
    }
    /**
     * Execute action.
     */
    public function execute()
    {
        $fileName = 'Zipcode.csv';
        $exportDirectory = $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/export/';
        $filePath = $exportDirectory . $fileName;

        $csvHeader = ['Zipcode', 'City', 'State', 'Country', 'Status'];
        $csvData = [];

        $collection = $this->entityFactory->create();
        $this->filter->getCollection($collection);

        foreach ($collection as $item) {
            $csvData[] = [
                $item->getCode(),
                $item->getCity(),
                $item->getState(),
                $item->getCountry(),
                $item->getStatus()
            ];
        }

        // Write CSV file
        $handle = $this->fileDriver->fileOpen($filePath, 'w');
        $this->fileDriver->filePutCsv($handle, $csvHeader);
        foreach ($csvData as $row) {
            $this->fileDriver->filePutCsv($handle, $row);
        }
        $this->fileDriver->fileClose($handle);

        // Download CSV file
        $fileContent = [
            'type' => 'filename',
            'value' => $filePath,
            'rm' => true
        ];
        $this->fileFactory->create($fileName, $fileContent, DirectoryList::VAR_DIR, 'application/octet-stream', null);
    }
}
