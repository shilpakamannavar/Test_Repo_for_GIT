<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Slider;


use Auraine\BannerSlider\Api\Data\SliderInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class MassDelete extends AbstractMassAction
{
    /**
     * @param \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection $collection
     * @return void
     */
    protected function processCollection(\Auraine\BannerSlider\Model\ResourceModel\Slider\Collection $collection): void
    {
        $itemsDeleted = 0;
        /** @var SliderInterface $item */
        foreach ($collection as $item) {
            try {
                $this->sliderRepository->delete($item);
                $itemsDeleted++;
            } catch (CouldNotDeleteException $e) {
                $this->messageManager->addErrorMessage(__('Error Deleting %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Slider(s) deleted', $itemsDeleted));
    }
}