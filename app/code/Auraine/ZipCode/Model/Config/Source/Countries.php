<?php
namespace Auraine\ZipCode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Countries implements OptionSourceInterface
{
    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $countryCollectionFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
    ) {
        $this->countryFactory = $countryFactory;
        $this->countryCollectionFactory = $countryCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $result = [];

        /** Displaying only India and not using country model as of now*/
        return [
            ['label' => __('India'), 'value' => 'IN']];
    }

    /**
     * Prepare array with country id and Name
     *
     * @return array
     */
    public function getOptions()
    {

        $countryModel = $this->countryFactory->create();
        $result = [];

        foreach ($this->countryCollectionFactory->create()->loadByStore()->getData() as $value => $label) {
            $country = $countryModel->loadByCode($label['country_id']);

            if ($country !== null) {
                $result[$label['country_id']] = $country->getName();
            }
        }

        return $result;
    }
}
