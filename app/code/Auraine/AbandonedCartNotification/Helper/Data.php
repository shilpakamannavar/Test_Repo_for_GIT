<?php
namespace Auraine\AbandonedCartNotification\Helper;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param TimezoneInterface $timezoneInterface
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        TimezoneInterface $timezoneInterface
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->timezoneInterface = $timezoneInterface;
    }

    /**
     * Send abandoned cart email
     *
     * @param string $mail
     * @param array $items
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMail($mail, $items)
    {

        $this->inlineTranslation->suspend();
        $transport = $this->transportBuilder
            ->setTemplateIdentifier(
                'abandoned_cart_notifications_attributes_emailtemplate'
            )
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => $items['storeId']

                ]
            )
            ->setTemplateVars(
                [
                    'name' => $items['name'],
                    'itemCount' => $items['itemCount']
                ]
            )
            ->setFrom(
                'general'
            )
            ->addTo($mail)
            ->getTransport();

        $transport->sendMessage();

        $this->inlineTranslation->resume();

        return "true";
    }
}
