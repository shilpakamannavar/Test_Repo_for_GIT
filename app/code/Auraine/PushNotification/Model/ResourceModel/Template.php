<?php
declare(strict_types=1);

namespace Auraine\PushNotification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Template extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('auraine_pushnotification_template', 'template_id');
    }
}

