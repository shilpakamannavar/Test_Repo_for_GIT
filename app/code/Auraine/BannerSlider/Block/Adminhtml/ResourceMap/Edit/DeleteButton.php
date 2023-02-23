<?php

namespace Auraine\BannerSlider\Block\Adminhtml\ResourceMap\Edit;

class DeleteButton extends GenericButton
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getResourceMapId()) {
            $data = [
                'label' => __('Delete Resource Map'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $this->getResourceMapId()]);
    }
}
