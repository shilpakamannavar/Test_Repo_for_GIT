<?php
namespace Auraine\ImageUploader\Block\Adminhtml\Form;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{

  /** @var UrlInterface */
    protected $urlInterface;

    public function __construct(
        UrlInterface $urlInterface
    ) {
        $this->urlInterface = $urlInterface;
    }
  /**
   * Button Data
   *
   * @return array
   */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
    
    /**
     * Backurl
     *
     * @return url
     */
    public function getBackUrl()
    {
        return $this->urlInterface->getUrl('*/*/');
    }
}
