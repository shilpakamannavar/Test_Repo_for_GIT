<?php

namespace Alternativetechlab\Mobilelogin\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Alternativetechlab\Mobilelogin\Helper\Data;

class Testapi extends Action
{
    private $helper;
    protected $resultJsonFactory;

    public function __construct(Context $context, Data $helper, JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        return $result->setData($this->helper->sendTestAPI());
    }

    protected function _isAllowed()
    {
        return true;
    }
}
