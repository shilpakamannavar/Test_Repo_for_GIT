<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Resmap;

use Auraine\BannerSlider\Api\Data\ResourceMapInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection;

class MassDelete extends AbstractMassAction
{
    /**
     * Process Mass Deletion
     *
     * @param \Auraine\BannerSlider\Model\ResourceModel\ResourceMap\Collection $collection
     * @return void
     */
    protected function processCollection(Collection $collection): void
    {
        $itemsDeleted = 0;
        /** @var ResourceMapInterface $item */
        foreach ($collection as $item) {
            try {
                $this->resourceMapRepository->delete($item);
                $itemsDeleted++;
            } catch (CouldNotDeleteException $e) {
                $this->messageManager
                     ->addErrorMessage(__('Error Deleting %1: %2', $item->getEntityId(), $e->getMessage()));
            }
        }
        $this->messageManager->addSuccessMessage(__('%1 Resource map(s) deleted', $itemsDeleted));
    }
}
