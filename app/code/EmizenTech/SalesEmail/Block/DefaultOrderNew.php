<?php

namespace EmizenTech\SalesEmail\Block;

use Magento\Framework\View\Element\Template;

/**
 * Sales Order Email items default renderer
 *
 * @author Auraine
 */
class DefaultOrderNew extends \Magento\Sales\Block\Order\Email\Items\Order\DefaultOrder
{
    
     /**
      * Get config
      *
      * @param string $path
      * @return string|null
      */
    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
