<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Zipcode implements ResolverInterface
{

    /**
     *
     * @var DataProvider\Zipcode
     */
    private $zipcodeDataProvider;

    /**
     * Cache instance
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Serializer instance
     *
     * @var Json
     */
    protected $json;

    /**
     * Cache key prefix
     */
    const CACHE_KEY_PREFIX = 'auraine_zipcode_';
     /**
      *
      * @param DataProvider\Zipcode $zipcodeDataProvider
      */
    public function __construct(
        DataProvider\Zipcode $zipcodeDataProvider,
        CacheInterface $cache,
        Json $json
    ) {
        $this->zipcodeDataProvider = $zipcodeDataProvider;
        $this->cache = $cache;
        $this->json = $json;
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
        $cacheKey = self::CACHE_KEY_PREFIX . $code;
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            $result = $this->json->unserialize($cachedData);
        } else {
            $cacheLifetime = 86400; // 1 day
            $result = $this->zipcodeDataProvider->generateZipCodeResponse($code);
            $this->cache->save($this->json->serialize($result), $cacheKey, [], $cacheLifetime);

        }

        return $result;
    }

    /**
     * Extracting pincode from request payload.
     *
     * @param array $args
     * @return int
     */
    private function getCode($args)
    {
        if (!isset($args['code'])) {
            throw new GraphQlInputException(__('Pincode should be specified should be specified'));
        }

        return $args['code'];
    }
}
