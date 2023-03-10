<?php

namespace Auraine\BannerSlider\Block\Widget\Slider\Banner\Renderer;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Block\Widget\Slider\Banner\RendererInterface;

class Video extends AbstractRenderer
{
    /**
     * @var $_template
     */
    protected $_template = 'Auraine_BannerSlider::widget/banner/renderer/youtube_video.phtml';
}
