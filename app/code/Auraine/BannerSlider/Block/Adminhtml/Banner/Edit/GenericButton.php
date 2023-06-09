<?php


namespace Auraine\BannerSlider\Block\Adminhtml\Banner\Edit;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

abstract class GenericButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param BannerRepositoryInterface $bannerRepository
     */
    public function __construct(
        Context $context,
        BannerRepositoryInterface $bannerRepository
    ) {
        $this->context = $context;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Get Banner Id
     *
     * @return int|null
     */
    protected function getBannerId()
    {
        try {
            return $this->bannerRepository
                ->loadById((int)$this->context->getRequest()->getParam('entity_id'))
                ->getEntityId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    protected function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
