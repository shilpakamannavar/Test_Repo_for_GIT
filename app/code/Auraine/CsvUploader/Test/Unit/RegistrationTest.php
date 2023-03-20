<?php
namespace Vendor\Module\Test;

class RegistrationTest extends \PHPUnit\Framework\TestCase
{
    public function testModuleIsRegistered()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $moduleStatus = $objectManager->get(\Magento\Framework\Module\Status::class);

        $this->assertTrue($moduleStatus->isEnabled('Auraine_CsvUploader'));
    }
}