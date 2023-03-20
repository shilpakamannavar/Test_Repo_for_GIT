<?php
namespace Alternativetechlab\Mobilelogin\Model\Config;

/**
 * Class Otplength
 * Alternativetechlab\Mobilelogin\Model\Config
 */
class Otplength implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        for ($len = 1; $len <=10; $len++) {
            $options[] = ['value' => $len,'label' => $len];
        }

        return $options;
    }
}
