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
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * Constructes model Pincode Model factory service
     * @param \Auraine\ZipCode\Model\PincodeFactory $pincodeFactory,
     * @param \Magento\Directory\Model\CountryFactory $countryFactory,
     */
    public function __construct(
        \Auraine\ZipCode\Model\PincodeFactory $pincodeFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
    )
    {
        $this->_pincodeFactory = $pincodeFactory;
        $this->_countryFactory = $countryFactory;

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
        
        $countryModel = $this->_countryFactory->create();
        $country = $countryModel->loadByCode($pincode->getCountry());

        return [
                'city' => $pincode->getCity(),
                'country' => $country->getName(),
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
