<?php
namespace Auraine\Brands\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const NAME = 'thumbnail';
    public const ALT_FIELD = 'name';
     /**
      * @var string
      */
    private $_getModel;
    /**
     * @var string
     */
    private $editUrl;
     /**
      * @var string
      */
    private $_objectManager = null;
    /**
     * @param ContextInterface $context
     *
     * @param UiComponentFactory $uiComponentFactory
     *
     * @param \Auraine\Brands\Model\Image\Image $imageHelper
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     *
     * @param ObjectManagerInterface $objectManager
     *
     * @param array $components
     *
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Auraine\Brands\Model\Brands\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
      //  $this->_getModel = $model;
        $this->_objectManager = $objectManager;
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $filename = $item['image'];
                $item[$fieldName . '_src'] = $this->imageHelper->getBaseUrl().$filename;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: $filename;
                $item[$fieldName . '_orig_src'] = $this->imageHelper->getBaseUrl().$filename;
            }
        }
        return $dataSource;
    }
   /**
    * Get Alt row function
    *
    * @param array $row
    *
    * @return null|string
    */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
