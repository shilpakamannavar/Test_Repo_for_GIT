<?php
namespace Auraine\Staticcontent\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Auraine\Staticcontent\Model\ResourceModel\Type\CollectionFactory as Content;

class TypeName implements OptionSourceInterface
{
    /**
     * colletionfactory of value
     *
     * @var $_typeName
     */
    protected $_typeName;

    /**
     * @param Context $context
     * @param CollectionFactory $typeCollectionFactory
     * @param TypeFactory $typeFactory
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    Content $typeName,
    array $data = []
    ) {
        $this->_typeName = $typeName;
    }
    
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];
        $typeModel = $this->_typeName->create();

        foreach ($typeModel as $value) {
            $result[] = [
                 'value' => $value['type'],
                 'label' => $value['type'],
             ];
        }

        return $result;
    }

}
