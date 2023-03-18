<?php
namespace Alternativetechlab\Textlocal\Model\Config;

class Promotion extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $options = [['value'=>'0', 'label'=>'Non Promotional']];
        $options[] = ['value' => '1','label' =>'Promotional'];
        return $options;
    }
}
