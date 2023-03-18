<?php

namespace Alternativetechlab\Mobilelogin\Block\Adminhtml\System\Config;

class Promotionalapi extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'Alternativetechlab_Mobilelogin::promotionalapibtn.phtml';

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('mobilelogin/index/promotionalapi');
    }

    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'promotionalapi',
                'label' => __('Send Promotional Message'),
                'class' => 'primary'
            ]
        );

        return $button->toHtml();
    }
}
