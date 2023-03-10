<?php
namespace Auraine\Staticcontent\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Auraine\Staticcontent\Model\ResourceModel\Type\CollectionFactory as Content;

class TypeName implements OptionSourceInterface
{
    /**
     * colletionfactory of value
     *
     * @var $typeName
     */
    protected $typeName;

    /**
     * @param Context $context
     * @param CollectionFactory $typeCollectionFactory
     * @param TypeFactory $typeFactory
     * @param array $data
     */
    public function __construct(
        Content $typeName,
       ) {
        $this->typeName = $typeName;
    }
    
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];
        $typeModel = $this->typeName->create();

        foreach ($typeModel as $value) {
            $result[] = [
                 'value' => $value['type'],
                 'label' => $value['type'],
             ];
        }

        return $result;
    }
}
