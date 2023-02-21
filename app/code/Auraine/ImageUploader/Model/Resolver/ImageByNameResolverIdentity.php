<?php
namespace Auraine\ImageUploader\Model\Resolver;

use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;

class ImageByNameResolverIdentity implements IdentityInterface
{
    const CACHE_TAG = 'auraine_imageuploader_image_by_name_resolver_identity';

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $cacheTag = self::CACHE_TAG;
    
    /**
     * Return a unique id for the model.
     *
     * @return array
     */
    public function getIdentities(array $resolvedData): array
    {
        $ids = [];
        $items = $resolvedData['items'] ?? [];
        foreach ($items as $item) {
            $ids[] = sprintf('%s_%s', $this->cacheTag, $item['content_id']);
        }
        if (!empty($ids)) {
            $ids[] = $this->cacheTag;
        }
        return $ids;
    }
}
