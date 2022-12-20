<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model\Resolver\DataProvider;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;

class Zipcode
{
 
    /**
     * Pincode Model factory
     * 
     * @var \Auraine\Zipcode\Model\PincodeFactory
     */
    protected $_pincodeFactory;

    /**
     * Constructes model Pincode Model factory service
     */
    public function __construct(
        \Auraine\ZipCode\Model\PincodeFactory $pincodeFactory
    )
    {
        $this->_pincodeFactory = $pincodeFactory;
    }

    /**
     * Checks the pincode available to ship.
     *
     * @param String $code
     * @return bool
     */
    private function isAvailable($code): bool
    {
        $pincode = $this->_pincodeFactory->create()->load($code, 'code');
        
        return $pincode->getStatus() ? (bool) $pincode->getStatus() : false;
    }

    /**
     * Checks & generates the response for pincode availability.
     *
     * @param String $code
     * @return array
     */
    public function generateZipCodeResponse($code): array
    {
        $pincode = $this->_pincodeFactory->create()->load($code, 'code');

        if (empty($pincode->getData())) {
            throw new GraphQlInputException(__("Pincode isn't available "));
        }

        return [
                'city' => $pincode->getCity(),
                'country' => $pincode->getCountry(),
                'status' => $pincode->getStatus()
            ];
    }

    /**
     * Extending the cart GraphQL with zipcode_check attribute.
     *
     * @param \Magento\Quote\Model\Quote $code
     * @return bool
     */
    public function isAvailableToShip($cart)
    {
        $shippingAddress = $cart->getShippingAddress();

        $code = $shippingAddress->getPostcode();

        if (!isset($shippingAddress) || is_null($code)) {
            return false; 
        }

        return $this->isAvailable($code);
    }
}

