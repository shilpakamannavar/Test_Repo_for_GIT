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
     * @param \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     */
    public function __construct(
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
        \Magento\Directory\Model\RegionFactory $regionFactory
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
        $regionCollection = $this->regionCollection->addCountryFilter('IN'); // Filter by country code 'IN'
        $regionModel = $this->regionFactory->create();
        $result = [];

        foreach ($regionCollection->getData() as $label) {
            $region = $regionModel->load($label['region_id']);

            if ($region !== null) {
                $result[$label['code']] = $label['name'];
            }
        }

        return $result;
    }
}
