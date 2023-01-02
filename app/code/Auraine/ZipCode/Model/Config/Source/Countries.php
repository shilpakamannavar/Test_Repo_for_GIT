<?php
namespace Auraine\ZipCode\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Countries implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_countryCollectionFactory;  

    /**
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $countryCollectionFactory
     * @param CountryFactory $countryFactory
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
    \Magento\Directory\Model\CountryFactory $countryFactory,
    array $data = []
    ) {
        $this->_countryFactory = $countryFactory;
        $this->_countryCollectionFactory = $countryCollectionFactory;
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

        $countryModel = $this->_countryFactory->create();
        $result = [];

        foreach ($this->_countryCollectionFactory->create()->loadByStore()->getData() as $value => $label) {
            $country = $countryModel->loadByCode($label['country_id']);

            if (!is_null($country)) {
                $result[$label['country_id']] = $country->getName();             
            }
        }

        return $result;
    }
}