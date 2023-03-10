<?php

namespace Auraine\BannerSlider\Ui\DataProvider\Banner\Form\Modifier;

use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class LocalImage implements ModifierInterface
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
     * LocalImage modify
     *
     * @param array $data
     * @return array
     * @since 100.1.0
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function modifyData(array $data)
    {
        foreach ($data as &$item) {
            $item = $this->processRow($item);
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
        $resourcePath = $data['resource_path'] ?? null;
        $resourceType = $data['resource_type'];
        if ($resourcePath && $resourceType === 'local_image') {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $url = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePath;
            $fileName = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($resourcePath);
            $fileExists = 'file_exists';
            $basename = 'basename';
            $filesize = 'filesize';
            if ($fileExists($fileName)) {
                $resourcePathData = [
                    'name' => $basename($fileName),
                    'url' => $url,
                    'size' => $filesize($fileName),
                    'type' => $this->mime->getMimeType($fileName)
                ];
                unset($data['resource_path']);
                $data['resource_path_local_image'][0] = $resourcePathData;
            }
        }
        $resourcePathMobile = $data['resource_path_mobile'] ?? null;
        $resourceTypeMobile = $data['resource_type'];
        if ($resourcePathMobile && $resourceTypeMobile === 'local_image') {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $urlMobile = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $resourcePathMobile;
            $fileNameMobile = $this->filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath($resourcePathMobile);
            $fileExistsMobile = 'file_exists';
            $basename = 'basename';
            $filesize = 'filesize';
            if ($fileExistsMobile($fileNameMobile)) {
                $resourcePathDataMobile = [
                    'name' => $basename($fileNameMobile),
                    'url' => $urlMobile,
                    'size' => $filesize($fileNameMobile),
                    'type' => $this->mime->getMimeType($fileNameMobile)
                ];
                unset($data['resource_path_mobile']);
                $data['resource_path_local_image_mobile'][0] = $resourcePathDataMobile;
            }
        }
        $resourcePathPoster = $data['resource_path_poster'] ?? null;
        if ($resourcePathPoster && $resourceType === 'video') {
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
