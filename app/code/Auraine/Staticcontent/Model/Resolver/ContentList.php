<?php
declare(strict_types=1);

namespace Auraine\Staticcontent\Model\Resolver;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;
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
     * @var $value
     */
    protected $value;

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
    const CACHE_KEY_PREFIX = 'auraine_staticcontent_contentlist_';

    public function __construct(
        CollectionFactory $value,
        CacheInterface $cache,
        Json $json
    ) {
        $this->value = $value;
        $this->cache = $cache;
        $this->json = $json;
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

        $cacheKey = self::CACHE_KEY_PREFIX . $content;
        $cachedData = $this->cache->load($cacheKey);
        if ($cachedData) {
            $result = $this->json->unserialize($cachedData);
        } else {
            $collection = $this->value->create()
                ->addFieldToFilter('type', $content)
                ->addFieldToFilter('enable', 1)
                ->setOrder('sortorder', 'ASC');

            $data = $collection->getData();

            $result = [];
            foreach ($data as $value) {
                $result[] = [
                    'label' => $value['label'],
                    'value' => $value['value'],
                ];
            }

            $this->cache->save($this->json->serialize($result), $cacheKey);
        }

        return $result;
    }

    private function getContent($args)
    {
        if (!isset($args['content'])) {
            throw new GraphQlInputException(__('Content should be specified'));
        }

        return $args['content'];
    }
}
