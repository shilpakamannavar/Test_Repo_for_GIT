<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePathPoster;

class ProcessorPoolPoster
{
    /**
     * @var ProcessorInterfacePoster[]
     */
    private $processors;

    /**
     * ProcessorPool constructor.
     *
     * @param array $processors
     */
    public function __construct(
        array $processors = []
    ) {
        $this->processors = $processors;
    }

    /**
     * Return an array
     *
     * @return ProcessorInterfacePoster[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }
}
