<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePath;

class ProcessorPool
{
    /**
     * @var ProcessorInterface[]
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
     * @return ProcessorInterface[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }
}
