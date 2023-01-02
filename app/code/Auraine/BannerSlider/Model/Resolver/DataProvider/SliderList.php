<?php
namespace Auraine\BannerSlider\Model\Resolver\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class SliderList
{
    protected $_sliderFactory;

    protected $_objectManager;

    public function __construct(
        \Auraine\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
        )
    {
        $this->_sliderFactory  = $sliderFactory;
        $this->_objectManager = $objectManager;
    }
 
    public function getSliderList()
    {
        $sliderData = [];
        try {
            $collection = $this->_sliderFactory->create();
            $sliderData = $collection->getData();
           

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $sliderData;
    } 
   
}