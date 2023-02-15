<?php
declare(strict_types=1);

namespace Auraine\PushNotification\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class TemplateList implements ResolverInterface
{
   /**
    * Template List
    *
    * @param Field $field , \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context,
    * ResolveInfo $info,array|null $value,array|null $args
    *
    * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
    *
    * @throws GraphQlInputException
    */
    /** Data provider for the
     *
     * @var dataProvider
     */
    private $dataProvider;
     /** Constructor function
      *
      * @param String $dataProvider
      */
    public function __construct(
        \Auraine\PushNotification\Model\Resolver\DataProvider\TemplateList $dataProvider
    ) {
        $this->dataProvider = $dataProvider;

    }
    /**
     * Resolver function for the list
     *
     * @param Field $field, $context, ResolveInfo $info, array $value , array $args
     *
     * @return dataProvider
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
      
         $template_id = $args['template_id'] ?? null;
                 
        return $this->dataProvider->getTemplateList($template_id);
    }
}
