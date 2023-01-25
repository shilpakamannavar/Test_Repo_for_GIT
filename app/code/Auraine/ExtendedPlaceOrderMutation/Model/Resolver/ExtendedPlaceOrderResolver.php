<?php
declare(strict_types=1);

namespace Auraine\ExtendedPlaceOrderMutation\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ExtendedPlaceOrderResolver implements ResolverInterface
{

    /**
     * @var \Magento\Sales\Model\Order
     */
    private $_order;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    private $_countryFactory;

    /**
     * Constructs rewards points will earn from the current cart items.
     *
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     */
    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->_order = $order;
        $this->_countryFactory = $countryFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($value['order_number'])) {
            return null;
        }

        $order = $this->_order->loadByIncrementId($value['order_number']);
        $shippingAddress = $order->getShippingAddress();

        if (empty($shippingAddress->getData())) {
            return null;
        }

        return $this->generateResponse($shippingAddress, $order->getCustomerEmail());
    }

    /**
     * Generate order details responce.
     *
     * @param \Magento\Sales\Model\Order\Address $shippingAddress
     * @param string $email
     * @return array
     */
    private function generateResponse($shippingAddress, $email)
    {
        return  [
                'firstname' => $shippingAddress->getFirstName(),
                'lastname' => $shippingAddress->getLastName(),
                'email' => $email,
                'mobile' => $shippingAddress->getTelephone(),
                'address' => [
                    'street' => implode(', ', $shippingAddress->getStreet()),
                    'city' => $shippingAddress->getCity(),
                    'region' => $shippingAddress->getRegion(),
                    'country' => $this->_countryFactory
                        ->create()
                        ->loadByCode($shippingAddress->getCountryId())
                        ->getName()
                ]
            ];
    }
}
