<?php
namespace Auraine\Category\Plugin;

use Magento\Catalog\Model\Category\DataProvider;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Store\Model\StoreManagerInterface;

class CategoryDataProvider
{
    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor creating
     *
     * @param CategoryHelper $categoryHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CategoryHelper $categoryHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->categoryHelper = $categoryHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * Add custom_image field to category data
     *
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(DataProvider $subject, $result)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        foreach ($result as &$category) {
            if (isset($category['image'])) {
                $category['custom_image'] = $mediaUrl . 'catalog/category/' . $category['image'];
            }
        }
        return $result;
    }
}
