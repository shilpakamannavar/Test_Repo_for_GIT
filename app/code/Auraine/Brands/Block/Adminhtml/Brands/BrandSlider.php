<?php
namespace Auraine\Brands\Block\Adminhtml\Brands;

use Magento\Framework\View\Element\Template;
use Auraine\BannerSlider\Model\ResourceModel\Slider\Grid\Collection;

class BrandSlider extends Template
{
    /**
     * @var sliderCollectionFactory
     */
    protected $sliderCollectionFactory;
     /** Constructor function for brand slider
      * @param \Magento\Backend\App\Action\Context $context
      * @param Collection $sliderCollectionFactory
      */
    public function __construct(
        Template\Context $context,
        Collection $sliderCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sliderCollectionFactory = $sliderCollectionFactory;
    }
    /** Function for getting all brand slider options
     *
     * @param sliderOptions $sliderOptions
     */
    public function getSliderOptions()
    {
        // Get the sliders collection
        $sliderCollection = $this->sliderCollectionFactory;

        // Add a filter for the page type
        $sliderCollection->addFieldToFilter('page_type', ['like' => '%brand_page%']);

        // Convert the collection to an options array
        $sliderOptions = [];
        foreach ($sliderCollection as $slider) {
            $sliderOptions[] = [
                'value' => $slider->getEntityId(),
                'label' => $slider->getTitle(),
            ];
        }

        return $sliderOptions;
    }
}
