<?php

namespace Auraine\BannerSlider\Block\Adminhtml\Banner\Edit;

class BackButton extends GenericButton
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
