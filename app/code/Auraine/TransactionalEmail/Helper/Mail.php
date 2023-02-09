<?php

declare(strict_types=1);

namespace Auraine\TransactionalEmail\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;

class Mail extends AbstractHelper
{

    protected $transportBuilder;
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder@param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param string $template configuration path of email template
     * @param string $sender configuration path of email identity
     * @param array $to email and name of the receiver
     * @param array $templateParams
     * @param int|null $storeId
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function sendEmailTemplate(
        $template,
        $sender,
        $to = [],
        $templateParams = [],
        $storeId = null
    ) {
        if (!isset($to['email']) || empty($to['email'])) {
            throw new LocalizedException(
                __('We could not send the email because the receiver data is invalid.')
            );
        }
        $storeId = $storeId ? $storeId : $this->storeManager->getStore()->getId();
        $name = isset($to['name']) ? $to['name'] : '';
        
        /** @var \Magento\Framework\Mail\TransportInterface $transport */
        $transport = $this->transportBuilder->setTemplateIdentifier(
            $this->scopeConfig->getValue($template, ScopeInterface::SCOPE_STORE, $storeId)
        )->setTemplateOptions(
            ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId]
        )->setTemplateVars(
            $templateParams
        )->setScopeId(
            $storeId
        )->setFrom(
            $this->scopeConfig->getValue($sender, ScopeInterface::SCOPE_STORE, $storeId)
        )->addTo(
            $to['email'],
            $name
        )->getTransport();
        $transport->sendMessage();
    }

    /**
     * Send the Shipping Email
     */
    public function sendShippingEmail(
        $sender = 'example@example.com',
        $to = ['email' => '', 'name' => '']
    ) {
        $this->sendEmailTemplate(
            'email/general/shipping',
            $sender,
            $to
        );
    }

    /**
     * Send the Order Email
     */
    public function sendOrderEmail(
        $sender = 'example@example.com',
        $to = ['email' => '', 'name' => '']
    ) {
        $this->sendEmailTemplate(
            'email/general/order',
            $sender,
            $to
        );
    }

    /**
     * Send the Return Email
     */
    public function sendReturnEmail(
        $sender = 'example@example.com',
        $to = ['email' => '', 'name' => '']
    ) {
        $this->sendEmailTemplate(
            'email/general/return',
            $sender,
            $to
        );
    }
}

