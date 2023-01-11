<?php
declare(strict_types=1);

namespace Auraine\Staticcontent\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Auraine\Staticcontent\Model\ResourceModel\Content\CollectionFactory;

class ContentList implements ResolverInterface
{
    /**
     * colletionfactory of value
     *
     * @var $_value
     */
    protected $_value;

    public function __construct(
        CollectionFactory $value
        ) {
            $this->_value = $value;
        }
 
   /**
    * @param Field $field
    * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
    * @param ResolveInfo $info
    * @param array|null $value
    * @param array|null $args
    * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
    * @throws GraphQlInputException
    */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $content = $this->getContent($args);

        $collection = $this->_value->create()
                        ->addFieldToFilter('type', $content)
                        ->addFieldToFilter('enable',1)
                        ->setOrder('sortorder','ASC');

        $data = $collection->getData();

        $result = [];
        foreach($data as $value) {
            $result[] = [
                    'label' => $value['label'],
                    'value' => $value['value'],
                ];
        }
        
      return $result;
    }

    private function getContent($args)
    {
        if(!isset($args['content'])) {
            throw new GraphQlInputException(__('Content should be specified'));
        }

        return $args['content'];
    }
}

