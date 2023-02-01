<?php

declare(strict_types=1);

namespace Auraine\ImageUploader\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Auraine\ImageUploader\Model\ResourceModel\Image\CollectionFactory ;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Model\UrlInterface;

class ImageByNameResolver implements ResolverInterface
{

    /**
     * @var CollectionFactory
     */
    private $imageCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * @param CollectionFactory $imageCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CollectionFactory $imageCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->imageCollectionFactory = $imageCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args) && $args['image_id'] === null) {
            throw new LocalizedException(__('"image_id" value should be specified'));
        }
        
        $result = $this->imageCollectionFactory
            ->create()
            ->addFieldToFilter('image_id', $args['image_id'])
            ->getData();
        
        $data =[];

        if ($result) {
            $data = [
                'image_id' => $result[0]['image_id'],
                'path'     => $this->storeManager
                                ->getStore()
                                ->getBaseUrl(
                                    UrlInterface::URL_TYPE_MEDIA
                                )
                                .$result[0]['path'],
                'name'     => $result[0]['name']
            ];
        }
       
        return $data;
    }
}
