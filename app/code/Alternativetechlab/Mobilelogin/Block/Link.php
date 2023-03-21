<?php
namespace Alternativetechlab\Mobilelogin\Block;

use Alternativetechlab\Mobilelogin\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Link
 * Alternativetechlab\Mobilelogin\Block
 */
class Link extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Link constructor.
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->helper->isEnable()) {
            if ($this->_request->getModuleName()=="mobilelogin") {
                $html = '<li class="nav item current">';
                $html .= '<strong>'. $this->escapeHtml($this->getLabel()).'</strong>';
                $html .= '</li>';
            } else {
                $html = "<li class='nav item'>";
                $html .= '<a href=' . $this->getUrl($this->getPath()) . ' >' .
                    $this->escapeHtml($this->getLabel()) . '</a>';
                $html .= '</li>';
            }

            return $html;
        }
    }
}
