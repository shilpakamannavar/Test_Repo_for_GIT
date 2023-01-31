<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Banner;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class MassDelete extends AbstractMassAction
{
    /**
     * Process Collection
     *
     * @param \Auraine\BannerSlider\Model\ResourceModel\Banner\Collection $collection
     * @return void
     */
    protected function processCollection(\Auraine\BannerSlider\Model\ResourceModel\Banner\Collection $collection): void
    {
        $itemsDeleted = 0;
        /** @var BannerInterface $item */
        foreach ($collection as $item) {
            try {
                $this->bannerRepository->delete($item);
                $itemsDeleted++;
            } catch (CouldNotDeleteException $e) {
                $this->messageManager
                     ->addErrorMessage(__('Error Deleting %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Banner(s) deleted', $itemsDeleted));
    }
}
