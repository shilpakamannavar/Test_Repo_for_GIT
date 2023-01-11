<?php
namespace Auraine\ZipCode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Regions implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_regionCollection;  

    /**
     * @var regionFactory
     */
    protected $_regionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $regionCollectionFactory
     * @param regionFactory $regionFactory
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
    \Magento\Directory\Model\RegionFactory $regionFactory,
    array $data = []
    ) {
        $this->_regionFactory = $regionFactory;
        $this->_regionCollection = $regionCollection;
    }
    
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];

        foreach ($this->getOptions() as $value => $label) {
            $result[] = [
                 'value' => $value,
                 'label' => $label,
             ];
        }

        return $result;
    }

    /**
     * Prepare array with country id and Name
     *
     * @return array
     */
    public function getOptions()
    {

        $regionModel = $this->_regionFactory->create();
        $result = [];

        foreach ($this->_regionCollection->getData() as $value => $label) {
            $region = $regionModel->load($label['region_id']);
            
            if (!is_null($region)) {
                $result[$label['region_id']] = $label['name'];             
            }
        }
       
        return $result;
    }
}