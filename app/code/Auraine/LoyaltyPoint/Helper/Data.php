<?php
namespace Auraine\LoyaltyPoint\Helper;

class Data
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * Constructs helper Service provider to fetch Store config values.
     * 
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Prepare array with tire slabs
     *
     * @return array
     */
    public function getSlabs()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_amount'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_amount'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_amount'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_amount'),
        ];
    }

    /**
     * Prepare array with tire slabs values
     *
     * @return array
     */
    public function getValues()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_value'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_value'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_value'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_value'),
        ];
    }

    /**
     * Fetch the store config data
     *
     * @param string $path
     * @return int
     */
    public function getStoreConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Prepare array with tire Names
     *
     * @return array
     */
    public function getNames()
    {
        return [
            1 => $this->getStoreConfigValue('auraine/tire_one/tire_one_name'),
            2 => $this->getStoreConfigValue('auraine/tire_two/tire_two_name'),
            3 => $this->getStoreConfigValue('auraine/tire_three/tire_three_name'),
            4 => $this->getStoreConfigValue('auraine/tire_four/tire_four_name'),
        ];
    }
}
