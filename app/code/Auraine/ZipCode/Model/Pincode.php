<?php
declare(strict_types=1);

namespace Auraine\ZipCode\Model;

use Auraine\ZipCode\Api\Data\PincodeInterface;
use Magento\Framework\Model\AbstractModel;

class Pincode extends AbstractModel implements PincodeInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Auraine\ZipCode\Model\ResourceModel\Pincode::class);
    }

    /**
     * @inheritDoc
     */
    public function getPincodeId()
    {
        return $this->getData(self::PINCODE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPincodeId($pincodeId)
    {
        return $this->setData(self::PINCODE_ID, $pincodeId);
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @inheritDoc
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}

