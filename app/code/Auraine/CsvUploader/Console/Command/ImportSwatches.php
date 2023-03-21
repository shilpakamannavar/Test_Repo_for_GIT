<?php

namespace Auraine\CsvUploader\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ImportSwatches Command
 */
class ImportSwatches extends Command
{
    const ATTRIBUTE_CODE_ARGUMENT = 'attributeCode';
    const FILE_PATH_ARGUMENT = 'filePath';
    const SWATCH_DIRECTORY = "attribute/swatch";

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    protected $eavAttribute;

    /**
     * @var $_scopeConfig
     */
    private $scopeConfig;

    protected $filesystem;
    protected $storeManager;

    /**
     * ImportSwatches constructor.
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param ResourceConnection $resource
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        ResourceConnection $resource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resource = $resource;
        $this->eavAttribute = $eavAttribute;
        parent::__construct();
        $this->scopeConfig = $scopeConfig;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('auraine:import:swatches')
            ->setDescription('Import Swatches from csv file')
            ->setDefinition([
                new InputArgument(
                    self::ATTRIBUTE_CODE_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'Attribute Code'
                ),
                new InputArgument(
                    self::FILE_PATH_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'File Path'
                )
            ]);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attributeCode = $input->getArgument(self::ATTRIBUTE_CODE_ARGUMENT);
        $filePath = $input->getArgument(self::FILE_PATH_ARGUMENT);
        if (!$attributeCode || !$filePath) {
            $output->writeln('Please specify attribute code and import file path (*.csv)');
            return false;
        }
        try {
            $this->importFromCsvFile($filePath, $attributeCode);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * @param $filePath
     * @param $attributeCode
     * @throws \Exception
     */
    public function importFromCsvFile($filePath, $attributeCode)
    {
        $customDirectory = $this->scopeConfig->getValue(
            'csv_upload/general/swatch_image_directory_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $ds = DIRECTORY_SEPARATOR;
        $attributeId = $this->eavAttribute->getIdByCode('catalog_product', $attributeCode);
        
        $importProductRawData = $this->csvProcessor->getData($this->getBasePath() . $ds . $filePath);
        foreach ($importProductRawData as $dataRow) {
            $connection = $this->resource->getConnection();
            $colorValue = addslashes($dataRow[0]);

            $imageUrl = $dataRow[1];
            $imagePath = $mediaDirectory->getAbsolutePath($customDirectory) . '/' . basename($imageUrl);
            if (!file_exists($imagePath)) {
                $mediaDirectory->create($customDirectory);
                $imageContent = file_get_contents($imageUrl);
                file_put_contents($imagePath, $imageContent);
            }

            $imageName = basename($imageUrl);
            $swatchImage = "/".$imageName;
            $optionId = $connection->fetchOne(
                "SELECT a.option_id
                FROM eav_attribute_option_value a
                JOIN eav_attribute_option b
                on a.option_id = b.option_id
                WHERE value='{$colorValue}' and b.attribute_id = $attributeId"
            );
            
            if ($optionId) {
                $swatchOptions = $connection->fetchAll(
                    "SELECT option_id,value FROM eav_attribute_option_swatch WHERE option_id={$optionId}"
                );
                if ($swatchOptions[0]['option_id'] == $optionId && $dataRow[1]) {
                    $connection->query(
                        "UPDATE eav_attribute_option_swatch
                        SET value= '{$swatchImage}' WHERE option_id = '{$optionId}'"
                    );
                    $connection->query(
                        "UPDATE eav_attribute_option_swatch
                        SET type= '2' WHERE option_id = '{$optionId}'"
                    );
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->directoryList->getRoot();
    }
}
