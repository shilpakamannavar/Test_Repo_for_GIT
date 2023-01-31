<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Slider;

use Auraine\BannerSlider\Api\Data\SliderInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class MassDisable extends AbstractMassAction
{
    /**
     * Process Collection
     *
     * @param \Auraine\BannerSlider\Model\ResourceModel\Slider\Collection $collection
     * @return void
     */
    protected function processCollection(\Auraine\BannerSlider\Model\ResourceModel\Slider\Collection $collection): void
    {
        $itemsSaved = 0;
        /** @var SliderInterface $item */
        foreach ($collection as $item) {
            try {
                if ($item->getIsEnabled()) {
                    $item->setIsEnabled(false);
                    $this->sliderRepository->save($item);
                    $itemsSaved++;
                }
            } catch (CouldNotSaveException $e) {
                $this->messageManager
                     ->addErrorMessage(__('Error saving %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Slider(s) disabled', $itemsSaved));
    }
}
