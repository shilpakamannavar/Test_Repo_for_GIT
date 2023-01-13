<?php
namespace Auraine\Brands\Test\Unit\Helper;

use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    protected $context;
    /**
     * __construct function
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }
    
    /**
     * Unit Test method
     *
     * @return string
     */
    public function unitTest()
    {
        return __("This is Unit Test");
    }
}
