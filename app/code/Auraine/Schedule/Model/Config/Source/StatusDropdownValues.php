<?php
namespace Auraine\Schedule\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class StatusDropdownValues extends AbstractSource
{
    public function getAllOptions()
    {
        $options = [
            [
                'value' => 'Pending',
                'label' => __('Pending')
            ],
            [
                'value' => 'Active',
                'label' => __('Active')
            ],
            [
                'value' => 'Inactive',
                'label' => __('Inactive')
            ]
        ];
        return $options;
    }
}
