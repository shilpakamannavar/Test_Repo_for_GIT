<?php
namespace Auraine\PushNotification\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CustomDropdownValues extends AbstractSource
{
    public function getAllOptions()
    {
        $options = [
            [
                'value' => 'All Pages',
                'label' => __('All Pages')
            ],
            [
                'value' => 'Home Page Only',
                'label' => __('Home Page Only')
            ],
            [
                'value' => 'Product Details Page Only',
                'label' => __('Product Details Page Only')
            ],
            [
                'value' => 'Cart Page Only',
                'label' => __('Cart Page Only')
            ],
            [
                'value' => 'Order Success Page Only',
                'label' => __('Order Success Page Only')
            ],
            [
                'value' => 'Custom Url',
                'label' => __('Custom Url')
            ]
        ];
        return $options;
    }
}