<?php
namespace Auraine\CustomImport\Model\Import\CustomImport;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    public const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
    public const ERROR_MESSAGE_IS_EMPTY = 'EmptyMessage';
    public const ERROR_TITLE_IS_EMPTY = 'Code is empty';
    /**
     * Initialize validator
     *
     * @return $context
     *
     * @param String $context
     */
    public function init($context);
}
