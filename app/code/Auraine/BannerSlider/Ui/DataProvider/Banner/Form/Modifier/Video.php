<?php

namespace Auraine\BannerSlider\Ui\DataProvider\Banner\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class Video implements ModifierInterface
{

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
            $resourcePath = $item['resource_path'] ?? null;
            $resourceType = $item['resource_type'];
            if ($resourcePath && $resourceType === 'video') {
                unset($item['resource_path']);
                $item['resource_path_video'] = $resourcePath;

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
