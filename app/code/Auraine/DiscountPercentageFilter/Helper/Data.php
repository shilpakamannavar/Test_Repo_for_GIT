<?php

namespace Auraine\DiscountPercentageFilter\Helper;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;

class Data extends AbstractHelper
{
    /**
     * @var $http
     */
    private $http;

    /**
     * @var $resolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Http $http
     * @param Resolver $resolver
     */
    public function __construct(
        Context $context,
        Http $http,
        Resolver $resolver
    ) {
        parent::__construct($context);
        $this->http = $http;
        $this->resolver = $resolver;
    }

    /**
     * Get Current Page Category
     *
     * @return boolean
     */
    public function isCurrentPageCategoryPage()
    {
        if ($this->http->getFullActionName() === 'catalog_category_view') {
            return true;
        }
        return false;
    }

    /**
     * Get Discount Variable
     *
     * @param [type] $discountPercentage
     * @return void
     */
    public function getDiscountVar($discountPercentage)
    {
        switch ($discountPercentage) {
            case ($discountPercentage >= 10 && $discountPercentage <20):
                $discountVar = '10% and above';
                break;
            case ($discountPercentage >= 20 && $discountPercentage <30):
                $discountVar = '20% and above';
                break;
            case ($discountPercentage >= 30 && $discountPercentage <40):
                $discountVar = '30% and above';
                break;
            case ($discountPercentage >= 40 && $discountPercentage <50):
                $discountVar = '40% and above';
                break;
            case ($discountPercentage >= 50 && $discountPercentage <60):
                $discountVar = '50% and above';
                break;
            case ($discountPercentage >= 60 && $discountPercentage <70):
                $discountVar = '60% and above';
                break;
            case ($discountPercentage >= 70 && $discountPercentage <80):
                $discountVar = '70% and above';
                break;
            case ($discountPercentage >= 80 && $discountPercentage <90):
                $discountVar = '80% and above';
                break;
            case ($discountPercentage >= 90 && $discountPercentage <= 100):
                $discountVar = '90% and above';
                break;
            default:
                $discountVar = null;
        }
        return $discountVar;
    }
}
