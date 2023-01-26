<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Resmap;


use Auraine\BannerSlider\Api\Data\ResourceMapInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class MassDelete extends AbstractMassAction
{
    /**
     * @param \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection $collection
     * @return void
     */
    protected function processCollection(\Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection $collection): void
    {
        $itemsDeleted = 0;
        /** @var ResourceMapInterface $item */
        foreach ($collection as $item) {
            try {
                $this->resourceMapRepository->delete($item);
                $itemsDeleted++;
            } catch (CouldNotDeleteException $e) {
                $this->messageManager->addErrorMessage(__('Error Deleting %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Resource map(s) deleted', $itemsDeleted));
    }
}