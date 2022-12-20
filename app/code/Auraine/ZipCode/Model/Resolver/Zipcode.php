<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Zipcode implements ResolverInterface
{

    /**
     * Zipcode data provider.
     *
     * @var \Auraine\ZipCode\Model\Resolver\DataProvider\Zipcode
     */
    private $zipcodeDataProvider;

     /**
     * Constructs a zipcode read service object.
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
        $code = $this->getCode($args);

        return $this->zipcodeDataProvider->generateZipCodeResponse($code);

    }

    /**
     * Extracting pincode from request payload.
     *
     * @param array $args
     * @return string
     */
    private function getCode($args)
    {
        if (!isset($args['code'])) {
            throw new GraphQlInputException(__('Pincode should be specified should be specified'));
        }
        
        return $args['code'];
    }
}

