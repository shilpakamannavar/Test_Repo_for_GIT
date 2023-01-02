<?php
declare(strict_types=1);

namespace Auraine\CustomerAvailable\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;


/**
 *
 */
class CustomerRecord implements ResolverInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $customerEmail = $this->getCustomerEmail($args);
        $salesData = $this->getCustomerData($customerEmail);
        return $salesData;
    }

    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getCustomerEmail(array $args): string {
        if (!isset($args['email'])) {
            throw new GraphQlInputException(__('Customer Email should be specified'));
        }
        return (string) $args['email'];
    }

    /**
     * @param string $customerEmail
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    private function getCustomerData(string $customerEmail): array
    {
         $websiteId = (int)$this->storeManager->getWebsite()->getId();
        try {
            if ($websiteId === null) {
                $websiteId = $this->storeManager->getStore()->getWebsiteId();

            }
            $customerEmail = $this->customerRepository->get($customerEmail, $websiteId);
            $customerData = [
                    'is_email_available' => !empty($customerEmail->getId()) ? 1 : 0
                ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $customerData;
    }
}
