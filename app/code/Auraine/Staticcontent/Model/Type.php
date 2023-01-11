<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Model;

use Auraine\Staticcontent\Api\Data\TypeInterface;
use Magento\Framework\Model\AbstractModel;

class Type extends AbstractModel implements TypeInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Auraine\Staticcontent\Model\ResourceModel\Type::class);
    }

    /**
     * @inheritDoc
     */
    public function getTypeId()
    {
        return $this->getData(self::TYPE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTypeId($typeId)
    {
        return $this->setData(self::TYPE_ID, $typeId);
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }
}

