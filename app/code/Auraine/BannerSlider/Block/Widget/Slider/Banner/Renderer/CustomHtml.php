<?php

namespace Auraine\BannerSlider\Block\Widget\Slider\Banner\Renderer;

use Auraine\BannerSlider\Api\Data\BannerInterface;
use Auraine\BannerSlider\Block\Widget\Slider\Banner\RendererInterface;

class CustomHtml extends AbstractRenderer
{
    /**
     * @var $_template
     */
    protected $_template = 'Auraine_BannerSlider::widget/banner/renderer/custom_html.phtml';
}
