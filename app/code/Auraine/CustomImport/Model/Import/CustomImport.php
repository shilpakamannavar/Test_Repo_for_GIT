<?php
namespace Auraine\CustomImport\Model\Import;

use Auraine\CustomImport\Model\Import\CustomImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;

class CustomImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    public const CODE = 'code';
    public const CITY = 'city';
    public const STATE = 'state';
    public const COUNTRY = 'country_id';
    public const STATUS = 'status';
    public const TABLE_ENTITY = 'pincode';
    public const DEFAULT_GBOOK_COUNTRY = 'IN';

    /** * Validation failure message template definitions *
     * @var string
     */
    protected $messageTemplates = [ ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'Code is empty',];
    /** *Validation for permanent Attributes *
     * @var string
     */
    protected $permanentAttributes = [self::CODE];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    /** Validation for group Factory
     * @var string
     */
    protected $groupFactory;
    /**
     * Validation for which is valid column names
     * @var string
     * @array
     */
    protected $validColumnNames = [
    self::CODE,
    self::CITY,
    self::STATE,
    self::COUNTRY,
    self::STATUS,
    ];

    /**
     * Need to log in import history
     *
     * @var logInHistory
     */
    protected $logInHistory = true;
    /**
     * Need to log in import history
     *
     * @var _validators
     */
    protected $_validators = [];
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    /**
     * Need to log in import history
     *
     * @var _resource
     */
    protected $_resource;

    /**
     * Constructor function
     *
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     *
     * @param JsonHelper $jsonHelper
     * @param ImportHelper $importExportData
     * @param Data $importData
     * @param ResourceConnection $resource
     * @param Helper $resourceHelper
     * @param StringUtils $string
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     *
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;

        $this->initMessageTemplates();
    }
    /**
     * Entity type code to get valid column names.
     *
     * @return string
     */
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'pincode';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */

   /**
    * Create Advanced message data from raw data.
    *
    * @throws \Exception
    * @return bool Result of operation.
    */
    protected function _importData()
    {
        $this->saveEntity();
        return true;
    }
    /**
     * Save entity
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Replace entity data
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Deletes entity data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                     $rowTtile = $rowData[self::CODE];
                     $listTitle[] = $rowTtile;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                     $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listTitle) {
            $this->deleteEntityFinish(array_unique($listTitle), self::TABLE_ENTITY);
        }
        return $this;
    }

    /**
     * Save and replace entity
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {

                    $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
                $rowTtile= $rowData[self::CODE];
                $listTitle[] = $rowTtile;
                $entityList[$rowTtile][] = [
                self::CODE => $rowData[self::CODE],
                self::CITY => $rowData[self::CITY],
                self::STATE => $rowData[self::STATE],
                self::COUNTRY => $rowData[self::COUNTRY],
                self::STATUS => $rowData[self::STATUS],
                ];
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($this->deleteEntityFinish(array_unique($listTitle), self::TABLE_ENTITY)) {
                    $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
            }
        }
        return $this;
    }

    /**
     * Save custom data.
     *
     * @param array $entityData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $entityRows) {
                foreach ($entityRows as $row) {
                    $entityIn[] = $row;
                }
            }
            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn, [
                    self::CODE,
                    self::CITY,
                    self::STATE,
                    self::COUNTRY,
                    self::STATUS
                ]);
            }

        }
        return $this;
    }

     /**
      * Delete custom data.
      *
      * @param array $ids
      *
      * @return bool
      */
    protected function deleteEntityFinish(array $ids, $table)
    {
        if ($table && $ids) {
            try {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName(self::TABLE_ENTITY),
                    $this->_connection->quoteInto(self::CODE . ' IN (?)', $ids)
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }

        } else {
            return false;
        }
    }
    /**
     * Pincode duplicate validation
     *
     * @param string $code
     *
     * @return int
     */
    protected function isPincodeExists($code)
    {
        $select = $this->_connection->select()->from(
            $this->_connection->getTableName(self::TABLE_ENTITY)
        )->where(
            self::CODE . ' = ?',
            $code
        );
        $result = $this->_connection->fetchAll($select);
        return count($result);
    }

    /**
     * Row validation
     *
     * @param array $rowData
     * @param int $rowNum
     *
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum): bool
    {
        $code = $rowData['code'] ?? '';
        $city = $rowData['city'] ?? '';
        $state =  $rowData['state'] ?? 0;
        $countryId = $rowData['country_id'] ?? '';
        $status = (int) $rowData['status'] ?? 0;

        if (!$code) {

            $this->addRowError('CodeIsRequired', $rowNum);
        }
        if ($code) {
            $count= $this->isPincodeExists($code);
            if ($count > 0) {
                $this->addRowError('CodeExists', $rowNum);
            }
        }
        /* Only for India - without space in between */
        if (!preg_match('/^[1-9]\d{5}$/', $code)) {
            $this->addRowError('CodeNotValid', $rowNum);
        }
        if (!$city) {
            $this->addRowError('CityIsRequired', $rowNum);
        }
        if (!$state) {
            $this->addRowError('StateIsRequired', $rowNum);
        }
        if ($state && !preg_match('/^[A-Z]{2}$/', $state)) {
            $this->addRowError('InvalidState', $rowNum);
        }

        if (!$countryId) {
            $this->addRowError('CountryIsRequired', $rowNum);
        }
        if ($countryId!=self::DEFAULT_GBOOK_COUNTRY) {
            $this->addRowError('CountryIsIndia', $rowNum);
        }
        if (!$status) {
            $rowData['status']=0;
        }
        if (!preg_match('/^[01]$/', $status)) {
            $this->addRowError('InvalidStatus', $rowNum);
        }
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Init Error Messages
     */
    private function initMessageTemplates()
    {
        $this->addMessageTemplate(
            'CodeIsRequired',
            __('The Pincode cannot be empty.')
        );
        $this->addMessageTemplate(
            'CityIsRequired',
            __('The City cannot be empty.')
        );
        $this->addMessageTemplate(
            'StateIsRequired',
            __('The State cannot be empty.')
        );
        $this->addMessageTemplate(
            'CountryIsRequired',
            __('The Country cannot be empty.')
        );
        $this->addMessageTemplate(
            'StatusIsRequired',
            __('The Status cannot be empty.')
        );
        $this->addMessageTemplate(
            'CodeNotValid',
            __('Invalid Pincode.')
        );
        $this->addMessageTemplate(
            'InvalidStatus',
            __('The Status should be 0 or 1.')
        );
        $this->addMessageTemplate(
            'InvalidState',
            __('Invalid State Code.')
        );
        $this->addMessageTemplate(
            'CountryIsIndia',
            __('Country id should be IN for India.')
        );
        $this->addMessageTemplate(
            'CodeExists',
            __('This pincode already exists in the database')
        );

    }
}
