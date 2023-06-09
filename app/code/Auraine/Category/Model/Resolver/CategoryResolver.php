<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Category\Model\Resolver;

use Exception;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Resolve rendered content for category attributes where HTML content is allowed
 */
class CategoryResolver implements ResolverInterface
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * Constructing
     *
     * @param CategoryRepository $categoryRepository, UrlInterface $urlBuilder
     *
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        UrlInterface $urlBuilder
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->urlBuilder = $urlBuilder;
    }
    /**
     * Resolver function
     *
     * @param Field $field, $context, ResolveInfo $info, array $value = null, array $args = null
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $category = $value['model'];
        $imageUrl = null;
        if ($category->getData('category_image_2')) {
            $imageUrl = rtrim($this->urlBuilder->getBaseUrl(), '/'). $category->getData('category_image_2');
        }
        return $imageUrl;
    }
}
