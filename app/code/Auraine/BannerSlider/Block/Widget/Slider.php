<?php

namespace Auraine\BannerSlider\Block\Widget;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Auraine\BannerSlider\Block\Widget\Slider\Banner\RendererPool;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Slider extends Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Auraine_BannerSlider::widget/slider.phtml';

    /**
     * @var string
     */
    protected $widgetUniqId;

    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;
    /**
     * @var RendererPool
     */
    private $rendererPool;
    /**
     * @var Template[]
     */
    private $suffixBlocks;

    /**
     * Slider constructor.
     * @param Template\Context $context
     * @param SliderRepositoryInterface $sliderRepository
     * @param RendererPool $rendererPool
     * @param array $suffixBlocks
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SliderRepositoryInterface $sliderRepository,
        RendererPool $rendererPool,
        array $suffixBlocks = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sliderRepository = $sliderRepository;
        $this->rendererPool = $rendererPool;
        $this->widgetUniqId = uniqid();
        $this->suffixBlocks = $suffixBlocks;
    }

    /**
     * Get Slider
     *
     * @return \Auraine\BannerSlider\Api\Data\SliderInterface|null
     */
    public function getSlider(): ?\Auraine\BannerSlider\Api\Data\SliderInterface
    {
        try {
            return $this->sliderRepository->loadById((int)$this->getData('slider_id'));
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
    /**
     * Return Class
     *
     * @return void
     */
    public function getClassName()
    {
        return 'Auraine-bannerslider';
    }

    /**
     * Render Function
     *
     * @param BannerInterface $banner
     * @param string $widgetClassName
     * @return string
     */
    public function render(BannerInterface $banner, $widgetClassName)
    {
        $renderer = $this->rendererPool->getRenderer($banner);
        return $renderer ? $renderer->render($banner, $widgetClassName) : '';
    }

    /**
     * Get Widget
     *
     * @return string
     */
    public function getWidgetUniqId(): string
    {
        return $this->widgetUniqId;
    }

    /**
     * Get Slider Option
     *
     * @return array
     */
    public function getSliderOptions()
    {
        $options = [
            'slidesToShow' => $this->getData('items_to_show'),
            'speed' => $this->getData('sliding_speed'),
            'autoplay' => $this->getData('autoplay') === '1',
            'autoplaySpeed' => $this->getData('autoplay_speed'),
            'fade' => $this->getData('animation_style') === 'fade',
            'arrows' => $this->getData('show_nav') === '1',
            'dots' => $this->getData('show_dots') === '1',
            'infinite' => true//$this->getData('infinite_scroll') === '1'
        ];
        return $options;
    }

    /**
     * Get Blocks
     *
     * @return Template[]
     */
    public function getSuffixBlocks(): array
    {
        return $this->suffixBlocks;
    }
}
