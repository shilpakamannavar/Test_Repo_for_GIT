<?php
declare(strict_types=1);

namespace Auraine\Brands\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;

class BrandsList implements ResolverInterface
{
   /**
    * Brand list
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
    const CACHE_KEY_PREFIX = 'auraine_brands_';
     /** Constructor function
      *
      * @param String $dataProvider
      */
    public function __construct(
        \Auraine\Brands\Model\Resolver\DataProvider\BrandsList $dataProvider,
        CacheInterface $cache,
        Json $json
    ) {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->json = $json;
    }
    /**
     * Resolver function for the list
     *
     * @param Field $field, context $context, ResolveInfo $info, array $value , array $args
     *
     * @return dataProvider
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
         
         
        $cacheKey = self::CACHE_KEY_PREFIX . hash('sha256', json_encode($args));
         $cachedData = $this->cache->load($cacheKey);
 
         if ($cachedData) {
             $result = $this->json->unserialize($cachedData);
         } else {
            $cacheLifetime = 86400; // 1 day
            $filterEntityId = $args['entity_id'] ?? null;
            $filterLabel = $args['filter_label'] ?? null;
            $filterUrl = $args['url_key'] ?? null;
            $result = $this->dataProvider->getBrandsList($filterEntityId, $filterLabel, $filterUrl);
            $this->cache->save($this->json->serialize($result), $cacheKey, [], $cacheLifetime);
           }
        return $result;
    }
}
