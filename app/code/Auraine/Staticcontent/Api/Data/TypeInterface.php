<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Auraine\Staticcontent\Api\Data;

interface TypeInterface
{

    const TYPE = 'type';
    const TYPE_ID = 'type_id';

    /**
     * Get type_id
     * @return string|null
     */
    public function getTypeId();

    /**
     * Set type_id
     * @param string $typeId
     * @return \Auraine\Staticcontent\Type\Api\Data\TypeInterface
     */
    public function setTypeId($typeId);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Auraine\Staticcontent\Type\Api\Data\TypeInterface
     */
    public function setType($type);
}

