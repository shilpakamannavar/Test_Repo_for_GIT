<?php
namespace Auraine\Category\Model\Resolver;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CategoryImageResolver
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
        ScopeConfigInterface $scopeConfig
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
    public function resolve(Category $category)
    {
        $imageUrl = null;
        $imagePath = $category->getData('category_image_2');
        if ($imagePath) {
            $mediaUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
            $imageUrl = $mediaUrl . $imagePath;
        }
        return $imageUrl;
    }
}

