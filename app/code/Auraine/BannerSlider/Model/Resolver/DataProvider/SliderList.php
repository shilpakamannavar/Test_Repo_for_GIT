<?php
namespace Auraine\BannerSlider\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class SliderList
{
    /**
     * @var $sliderFactory
     */
    protected $sliderFactory;

    /**
     * @var $objectManager
     */
    protected $objectManager;

  /**
   * Slider constructor.
   *
   * @param \Auraine\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderFactory
   * @param \Magento\Framework\ObjectManagerInterface $objectManager
   */
    public function __construct(
        \Auraine\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->sliderFactory  = $sliderFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * Get Slider List
     *
     * @param [type] $filter_entity_id
     * @return array
     */
    public function getSliderList($filterEntityId)
    {
        $sliderData = [];
        try {
            $collection = $this->sliderFactory->create();
            $sliderData = $collection->getData();
            if ($filterEntityId) {
                $collection = $this->sliderFactory->create()->addFieldToFilter('entity_id', $filterEntityId);
                $sliderData = $collection->getData();
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $sliderData;
    }
}
