<?php
namespace Auraine\Category\Plugin;

use Magento\Catalog\Model\Category\DataProvider;
use Magento\Catalog\Helper\Category as CategoryHelper;

class CategoryDataProvider
{
    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(CategoryHelper $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }

    public function afterGetData(DataProvider $subject, $result)
    {
        $mediaUrl = $subject->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        foreach ($result as &$category) {
            if (isset($category['image'])) {
                $category['custom_image'] = $mediaUrl . 'catalog/category/' . $category['image'];
            }
        }
        return $result;
    }
}
