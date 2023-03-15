<?php
namespace Auraine\Schedule\Controller\Adminhtml\Schedule;

class NewSchedule extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Auraine_Schedule::newschedule';

    const PAGE_TITLE = 'Page Title';

    const CRONJOBCLASS = 'Auraine\Schedule\Cron\BannerSchedular';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $cron = \Magento\Framework\App\ObjectManager::getInstance()->create(self::CRONJOBCLASS);
        $resultRedirect = $this->resultRedirectFactory->create();
        $cron->execute();
         $this->_response;
         return $resultRedirect->setPath('*/*/');
    }
      
    /**Is allowes
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Auraine_Schedule::schedule_newschedule');
    }
}
