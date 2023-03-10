<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Controller\Adminhtml;

abstract class Pincode extends \Magento\Backend\App\Action
{

    /**
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    public const ADMIN_RESOURCE = 'Auraine_ZipCode::top_level';

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Auraine'), __('Auraine'))
            ->addBreadcrumb(__('Pincode'), __('Pincode'));
        return $resultPage;
    }
}
