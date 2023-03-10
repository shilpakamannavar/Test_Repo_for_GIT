<?php

namespace Auraine\BannerSlider\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Auraine\BannerSlider\Model\Resolver\DataProvider\Slider as SliderDataProvider;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;

class BannerSlider implements ResolverInterface
{
    /**
     * @var SliderDataProvider
     */
    private $sliderDataResolver;
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
    const CACHE_KEY_PREFIX = 'auraine_bannerSlider_';

    /**
     * BannerSlider constructor.
     * @param SliderDataProvider $sliderDataResolver
     */
    public function __construct(
        SliderDataProvider $sliderDataResolver,
        CacheInterface $cache,
        Json $json
    ) {
        $this->sliderDataResolver = $sliderDataResolver;
        $this->cache = $cache;
        $this->json = $json;
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return mixed|Value
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $cacheKey = self::CACHE_KEY_PREFIX . hash('sha256', json_encode($args));
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            $result = $this->json->unserialize($cachedData);
        } else {
            $cacheLifetime = 86400; // 1 day
            $result = $this->sliderDataResolver->getData($args);
            $this->cache->save($this->json->serialize($result), $cacheKey, [], $cacheLifetime);
        }
        
        return $result;
    }
}
