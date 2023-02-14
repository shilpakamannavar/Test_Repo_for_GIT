<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePathMobile;

class ProcessorPoolMobile
{
    /**
     * @var ProcessorInterfaceMobile[]
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
     * @return ProcessorInterfaceMobile[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }
}
