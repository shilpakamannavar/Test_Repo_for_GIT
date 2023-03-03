<?php
declare(strict_types=1);

namespace Auraine\BannerSlider\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;

class SliderList implements ResolverInterface
{
   /**
    * @var $dataProvider
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
    const CACHE_KEY_PREFIX = 'auraine_sliderlist_';
    /**
     * Slider List Constructor.
     *
     * @param \Auraine\BannerSlider\Model\Resolver\DataProvider\SliderList $dataProvider
     */
    public function __construct(
        \Auraine\BannerSlider\Model\Resolver\DataProvider\SliderList $dataProvider,
        CacheInterface $cache,
        Json $json
    ) {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->json = $json;
    }

    /**
     * Checking for Entity ID
     *
     * @param Field $field
     * @param context $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $filterEntityId = $args['entity_id'] ?? null;

        $cacheKey = self::CACHE_KEY_PREFIX . $filterEntityId;
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            $result = $this->json->unserialize($cachedData);
        } else {
            $result = $this->dataProvider->getSliderList($filterEntityId);
            $this->cache->save($this->json->serialize($result), $cacheKey);
        }
        return $result;
    }
}
