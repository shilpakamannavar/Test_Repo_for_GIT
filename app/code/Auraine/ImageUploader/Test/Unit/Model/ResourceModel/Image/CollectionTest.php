<?php

namespace Auraine\ImageUploader\Test\Unit\Model\ResourceModel\Image;

use Auraine\ImageUploader\Model\ResourceModel\Image\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp(): void
    {
        $this->collection = $this->getMockBuilder(Collection::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * Test the class instance and parent class.
     */
    public function testInstance()
    {
        $this->assertInstanceOf(Collection::class, $this->collection);
        $this->assertInstanceOf(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::class, $this->collection);
    }
}

