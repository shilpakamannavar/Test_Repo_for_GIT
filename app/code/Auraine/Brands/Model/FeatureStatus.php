<?php

namespace Auraine\Brands\Model;

use Magento\Framework\Data\OptionSourceInterface;

class FeatureStatus implements OptionSourceInterface
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = ['1' => __('Yes'),'0' => __('No')];
        return $options;
    }


    public function getOptionArrayYesNo()
    {
        $options = ['1' => __('Yes'),'0' => __('No')];
        return $options;
    }
    public function getOptionsForYesNo()
    {
        $res = [];
        foreach ($this->getOptionArrayYesNo() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * Get Grid row status labels array with empty value for option element.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}
