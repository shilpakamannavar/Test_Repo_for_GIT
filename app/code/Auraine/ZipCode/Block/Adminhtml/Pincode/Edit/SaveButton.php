<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Block\Adminhtml\Pincode\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * Get Save button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Pincode'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
