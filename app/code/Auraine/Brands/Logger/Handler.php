<?php
namespace Auraine\Brands\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level.
     *
     * @var int
     */
    public $loggerType = Logger::INFO;

    /**
     * giving file name
     *
     * @var string
     */
    public $fileName = '/var/log/grid.log';
}
