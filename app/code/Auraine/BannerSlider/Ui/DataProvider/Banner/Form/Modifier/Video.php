<?php


namespace Auraine\BannerSlider\Ui\DataProvider\Banner\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Video implements ModifierInterface
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Mime
     */
    private $mime;

     /**
      *
      * LocalImage constructor.
      * @param Filesystem $filesystem
      * @param StoreManagerInterface $storeManager
      * @param Mime $mime
      */
    public function __construct(
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        Mime $mime
    ) {
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->mime = $mime;
    }

    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        foreach ($data as &$item) {
            $item = $this->processRow($item);
            $resourcePathPoster = $item['resource_path_poster'] ?? null;
            $resourceType = $item['resource_type'];
            if (($resourcePathPoster && $resourceType === 'video') || ($resourcePathPoster && $resourceType === 'youtube_video')) {
                unset($item['resource_path_poster']);
                $item['resource_path_poster_image'] = $resourcePathPoster;
            }
        }
        return $data;
    }

    /**
     * Process Data
     *
     * @param array $data
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function processRow($data)
    {
        $resourcePathPoster = $data['resource_path_poster'] ?? null;
        $resourceType = $data['resource_type'];
        if (($resourcePathPoster && $resourceType === 'video')  || ($resourcePathPoster && $resourceType === 'youtube_video')) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $urlPoster = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePathPoster;
            $fileNamePoster = $this->filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath($resourcePathPoster);
            $fileExistsPoster = 'file_exists';
            $basename = 'basename';
            $filesize = 'filesize';
            if ($fileExistsPoster($fileNamePoster)) {
                $resourcePathDataPoster = [
                    'name' => $basename($fileNamePoster),
                    'url' => $urlPoster,
                    'size' => $filesize($fileNamePoster),
                    'type' => $this->mime->getMimeType($fileNamePoster)
                ];
                unset($data['resource_path_poster']);
                $data['resource_path_poster_image'][0] = $resourcePathDataPoster;
            }
        }
        return $data;
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
