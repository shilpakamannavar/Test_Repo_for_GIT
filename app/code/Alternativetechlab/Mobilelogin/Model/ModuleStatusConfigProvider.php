<?php

namespace Alternativetechlab\Mobilelogin\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class ModuleStatusConfigProvider implements ConfigProviderInterface
{
    const XML_MODULE_STATUS_PATH = "mobilelogin/moduleoption/enable";

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ModuleStatusConfigProvider constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $config = [];

        $moduleIsEnabled = $this->checkIsModuleEnabled(self::XML_MODULE_STATUS_PATH);
        if ($moduleIsEnabled) {
            $config['moduleStatus'] = true;
        } else {
            $config['moduleStatus'] = false;
        }

        return $config;
    }

    public function checkIsModuleEnabled($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
