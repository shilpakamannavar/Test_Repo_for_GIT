<?php
namespace Auraine\Category\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * Store Manager Instance
     * @var string storeManager
     */
    protected $storeManager;
    /**
     * @param StoreManagerInterface $storeManager
     *
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }
    /**
     * Fuction for getting base url
     *
     * @param getStore $getStore
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
