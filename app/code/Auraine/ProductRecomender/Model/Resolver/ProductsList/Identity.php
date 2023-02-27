<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\ProductRecomender\Model\Resolver\ProductsList;

use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;

/**
 * Identity for resolved fb
 */
class Identity implements IdentityInterface
{
    /** @var string */
    private $cacheTag = 'frequent_b';

    /**
     * Get identities from resolved data
     *
     * @param array $resolvedData
     * @return string[]
     */
    public function getIdentities(array $resolvedData): array
    {
        $ids = [];
        $items = $resolvedData['items'] ?? [];
        foreach ($items as $item) {
            $ids[] = sprintf('%s_%s', $this->cacheTag, $item['entity_id']);
        }
        if (!empty($ids)) {
            array_unshift($ids, $this->cacheTag);
        }
        return $ids;
    }
}
