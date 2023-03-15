<?php
namespace Auraine\Category\Model\Resolver;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CategoryImageResolver implements ResolverInterface
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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;


    /**
     * CategoryImageResolver constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param UrlInterface $urlBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Category $category
     *
     * @return string|null
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $imageUrl = null;
        /* @var $category Category */
        $category = $value['model'];
        $imagePath = $category->getData('category_image_2');
       if ($imagePath) {
            $mediaUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
            $imageUrl = $mediaUrl . $imagePath;
        }
        return $imageUrl;
    }
}

