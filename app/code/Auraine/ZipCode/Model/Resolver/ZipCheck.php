<?php
namespace Auraine\ZipCode\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ZipCheck implements ResolverInterface
{
 
    /**
     * Zipcode data provider.
     *
     * @var \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode
     */
    private $zipcodeDataProvider;

     /**
     * Constructs a coupon read service object.
     */
    public function __construct(
        DataProvider\Zipcode $zipcodeDataProvider
    ) {
        $this->zipcodeDataProvider = $zipcodeDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info, 
        array $value = null, 
        array $args = null
    ) {

        return $this->zipcodeDataProvider->isAvailableToShip($this->getCart($value));

    }

    /**
     * Extracting cart Object value array.
     *
     * @param array $value
     * @return \Magento\Quote\Model\Quote
     */
    private function getCart($value)
    {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value must be specified'));
        }

        return $value['model'];
    }
}
