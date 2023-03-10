<?php


namespace Auraine\BannerSlider\Ui\Component\Listing\Column\Banner;

use Auraine\BannerSlider\Ui\Component\Listing\Column\Banner\ResourcePathMobile\ProcessorInterfaceMobile;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ResourcePathMobile extends Column
{
    /**
     * @var ProcessorInterfaceMobile[]
     */
    private $processors;

    /**
     * ResourcePath constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param array $processors
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        array $processors = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->processors = $processors;
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($this->processors[$item['resource_type']])) {
                    
                    $item['resource_path_mobile'] = $this->processors[$item['resource_type']]->process($item);
                    
                }
            }
        }

        return $dataSource;
    }
}
