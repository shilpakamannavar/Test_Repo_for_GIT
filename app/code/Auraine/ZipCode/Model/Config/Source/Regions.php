<?php
namespace Auraine\ZipCode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Regions implements OptionSourceInterface
{
    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\Collection
     */
    protected $regionCollection;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $regionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        array $data = []
    ) {
        $this->regionFactory = $regionFactory;
        $this->regionCollection = $regionCollection;
    }
    
    /**
     * @inheritdoc
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

        $regionModel = $this->regionFactory->create();
        $result = [];

        foreach ($this->regionCollection->getData() as $value => $label) {
            $region = $regionModel->load($label['region_id']);
            
            if ($region !== null) {
                $result[$label['region_id']] = $label['name'];
            }
        }
       
        return $result;
    }
}
