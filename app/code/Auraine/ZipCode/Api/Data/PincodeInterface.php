<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Api\Data;

interface PincodeInterface
{

    const PINCODE_ID = 'pincode_id';
    const STATUS = 'status';
    const CODE = 'code';
    const COUNTRY = 'country';
    const CITY = 'city';
    const STATE = 'state';

    /**
     * Get pincode_id
     * @return string|null
     */
    public function getPincodeId();

    /**
     * Set pincode_id
     * @param string $pincodeId
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setPincodeId($pincodeId);

    /**
     * Get code
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     * @param string $code
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setCode($code);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setCity($city);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setCountry($country);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setStatus($status);

     /**
     * Get State
     * @return string|null
     */
    public function getState();

    /**
     * Set State
     * @param string $State
     * @return \Auraine\ZipCode\Pincode\Api\Data\PincodeInterface
     */
    public function setState($state);
}

