<?php
namespace Auraine\BannerSlider\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class SliderList
{
    /**
     * @var $_sliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var $_objectManager
     */
    protected $_objectManager;

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
        $this->_sliderFactory  = $sliderFactory;
        $this->_objectManager = $objectManager;
    }

    /**
     * Get Slider List
     *
     * @param [type] $filter_entity_id
     * @return void
     */
    public function getSliderList($filter_entity_id)
    {
        $sliderData = [];
        try {
            $collection = $this->_sliderFactory->create();
            $sliderData = $collection->getData();
            if ($filter_entity_id) {
                $collection = $this->_sliderFactory->create()->addFieldToFilter('entity_id', $filter_entity_id);
                $brandData = $collection->getData();
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $sliderData;
    }
}
