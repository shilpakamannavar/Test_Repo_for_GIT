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
    protected $pincodeFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $regionFactory;

    /**
     * Constructes model Pincode Model factory service
     *
     * @param \Auraine\ZipCode\Model\PincodeFactory $pincodeFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     */
    public function __construct(
        \Auraine\ZipCode\Model\PincodeFactory $pincodeFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
    ) {
        $this->pincodeFactory = $pincodeFactory;
        $this->countryFactory = $countryFactory;
        $this->regionFactory = $regionFactory;
    }

    /**
     * Checks the pincode available to ship.
     *
     * @param String $code
     * @return bool
     */
    private function isAvailable($code): bool
    {
        $pincode = $this->pincodeFactory->create()->load($code, 'code');

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

        $pincode = $this->pincodeFactory->create()->load($code, 'code');

        if (empty($pincode->getData())) {
            throw new GraphQlInputException(__("Pincode isn't available "));
        }

        $countryId=$pincode->getCountry();
        $stateId=$pincode->getState();

        $countryModel = $this->countryFactory->create();
        $country = $countryModel->loadByCode($countryId);
        $region = $this->regionFactory->create()->load($stateId);

        return [
                'city' => $pincode->getCity(),
                'country' => $country->getName(),
                'status' => $pincode->getStatus(),
                'state' => $region->getName(),
                'country_id' => $countryId,
                'state_id' => $stateId
            ];
    }

    /**
     * Extending the cart GraphQL with zipcode_check attribute.
     *
     * @param \Magento\Quote\Model\Quote $cart
     * @return bool
     */
    public function isAvailableToShip($cart)
    {
        $shippingAddress = $cart->getShippingAddress();

        $code = $shippingAddress->getPostcode();

        if (!isset($shippingAddress) || $code === null) {
            return false;
        }

        return $this->isAvailable($code);
    }
}
