<?php

namespace Auraine\AbandonedCartNotification\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Hours implements OptionSourceInterface
{
    
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [];
        $hours = 0;
        while ($hours < 24) {
            $options[] = ['label' => __($hours + 1), 'value' => $hours];
            $hours++;
        }

        return $options;
    }
}
