<?php


namespace Auraine\BannerSlider\Controller\Adminhtml\Banner;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class MassDisable extends AbstractMassAction
{
    /**
     * @param \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection $collection
     * @return void
     */
    protected function processCollection(\Auraine\BannerSlider\Model\ResourceModel\Banner\Collection $collection): void
    {
        $itemsSaved = 0;
        /** @var BannerInterface $item */
        foreach ($collection as $item) {
            try {
                if ($item->getIsEnabled()) {
                    $item->setIsEnabled(false);
                    $this->bannerRepository->save($item);
                    $itemsSaved++;
                }
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__('Error saving %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Banner(s) disabled', $itemsSaved));
    }
}