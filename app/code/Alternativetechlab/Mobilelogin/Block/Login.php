<?php
namespace Alternativetechlab\Mobilelogin\Block;

use Magento\Framework\View\Element\Template\Context;

/**
 * Class Mobilelogin
 * Alternativetechlab\Mobilelogin\Block
 */
class Login extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Alternativetechlab\Mobilelogin\Helper\Data
     */
    protected $helper;

    /**
     * Mobilelogin constructor.
     * @param Context $context
     * @param \Alternativetechlab\Mobilelogin\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        \Alternativetechlab\Mobilelogin\Helper\Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getLayoutType()
    {
        return $this->helper->getLayoutType();
    }

    /**
     * @return mixed
     */
    public function getOtpStringlenght()
    {
        return $this->helper->getOtpStringlenght();
    }

    /**
     * @return mixed
     */
    public function getRegTemplateImage()
    {
        return $this->helper->getRegTemplateImage();
    }
    public function getLoginTemplateImage()
    {
        return $this->helper->getLoginTemplateImage();
    }
    public function getForgotTemplateImage()
    {
        return $this->helper->getForgotTemplateImage();
    }

    /**
     * @return mixed
     */
    public function getImageType()
    {
        return $this->helper->getImageType();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        $currentStore = $this->storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        return $this->helper->isEnable();
    }

    /**
     * @return mixed
     */
    public function isMobileEnable()
    {
        return $this->helper->isMobileEnable();
    }

    /**
     * @return mixed
     */
    public function getGeoCountryCode()
    {
        return $this->helper->getGeoCountryCode();
    }

    /**
     * @return mixed
     */
    public function getAllowCountry()
    {
        return $this->helper->getAllowCountry();
    }

    /**
     * @return array|string
     */
    public function getApplicableCountry()
    {
        return $this->helper->getApplicableCountry();
    }

    /**
     * @return array|string
     */
    public function getApplicableCountryJson()
    {
        return $this->helper->getApplicableCountry(false);
    }
}
