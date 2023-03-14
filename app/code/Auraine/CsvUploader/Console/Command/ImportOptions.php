<?php
namespace Auraine\CsvUploader\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportOptions Command
 */
class ImportOptions extends Command
{
    /**
     * Name argument
     */
    const ATTRIBUTE_CODE_ARGUMENT = 'attribute_code';

    /**
     * File Path argument constant
     */
    const FILE_PATH_ARGUMENT = 'file_path';

    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\Source\TableFactory
     */
    protected $tableFactory;

    /**
     * @var \Magento\Eav\Api\AttributeOptionManagementInterface
     */
    protected $attributeOptionManagement;

    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory
     */
    protected $optionFactory;

    /**
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \Magento\Eav\Model\Entity\Attribute\Source\TableFactory $tableFactory
     * @param \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement
     * @param \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory
     * @param \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory
     */
    public function __construct(
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Eav\Model\Entity\Attribute\Source\TableFactory $tableFactory,
        \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement,
        \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory
    ) {
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->attributeRepository = $attributeRepository;
        $this->tableFactory = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('auraine:import:options')
            ->setDescription('Import Product Attribute Options from csv file')
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
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attribute_code = $input->getArgument(self::ATTRIBUTE_CODE_ARGUMENT);
        $file_path = $input->getArgument(self::FILE_PATH_ARGUMENT);

        if (!$attribute_code || !$file_path) {
            $output->writeln('Please specify attribute code and import file path (*.csv)');
        } else {
            $this->importFromCsvFile($file_path, $attribute_code);
        }
        return null;
    }

    /**
     * @param $filePath
     * @param $attributeCode
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importFromCsvFile($filePath, $attributeCode)
    {
        $ds = DIRECTORY_SEPARATOR;
        $importProductRawData = $this->csvProcessor->getData($this->getBasePath() . $ds . $filePath);
        $sortOrder = 0;
        foreach ($importProductRawData as $dataRow) {
            $storeLabel = null;
            if (isset($dataRow[1])) {
                $storeLabel = $dataRow[1];
            }
            $this->createOrGetId($attributeCode, $dataRow[0], $storeLabel, $sortOrder++);
        }
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->directoryList->getRoot();
    }

    /**
     * @param $attributeCode
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAttribute($attributeCode)
    {
        return $this->attributeRepository->get($attributeCode);
    }

    /**
     * @param $attributeCode
     * @param $label
     * @param bool $force
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOptionId($attributeCode, $label, $force = false)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->getAttribute($attributeCode);

        // Build option array if necessary
        if ($force === true || !isset($this->attributeValues[$attribute->getAttributeId()])) {
            $this->attributeValues[$attribute->getAttributeId()] = [];

            // We have to generate a new sourceModel instance each time through to prevent it from
            // referencing its _options cache. No other way to get it to pick up newly-added values.

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[$attribute->getAttributeId()][$option['label']] = $option['value'];
            }
        }

        // Return option ID if exists
        if (isset($this->attributeValues[$attribute->getAttributeId()][$label])) {
            return $this->attributeValues[$attribute->getAttributeId()][$label];
        }

        // Return false if does not exist
        return false;
    }

    /**
     * @param $attributeCode
     * @param $label
     * @param $storeLabel
     * @param int $sortOrder
     * @return bool
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createOrGetId($attributeCode, $label, $storeLabel, $sortOrder = 0)
    {
        if (strlen($label) < 1) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Label for %1 must not be empty.', $attributeCode)
            );
        }
        $optionId = $this->getOptionId($attributeCode, $label);
        if (!$optionId) {
            /** @var \Magento\Eav\Model\Entity\Attribute\Option $option */
            $option = $this->optionFactory->create();
            $option->setLabel($label);
            $option->setSortOrder($sortOrder);
            $option->setIsDefault(false);
            $optionLabel = $this->optionLabelFactory->create();
            $option->setStoreLabels([$optionLabel->setLabel($storeLabel)->setStoreId(1)]);
            $this->attributeOptionManagement->add(
                \Magento\Catalog\Model\Product::ENTITY,
                $this->getAttribute($attributeCode)->getAttributeId(),
                $option
            );
            $optionId = $this->getOptionId($attributeCode, $label, true);
            return $optionId;
        }
        return false;
    }
}
