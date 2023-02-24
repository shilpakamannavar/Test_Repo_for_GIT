<?php

namespace Auraine\BannerSlider\Model\Banner\ResourcePathPoster\Processor;

use Auraine\BannerSlider\Model\Banner\ResourcePathPoster\ProcessorInterfacePoster;
use Auraine\BannerSlider\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Cms\Helper\Wysiwyg\Images as WysiwygImageHelper;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Video implements ProcessorInterfacePoster
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var WysiwygImageHelper
     */
    private $wysiwygImageHelper;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * LocalImage constructor.
     * @param ImageUploader $imageUploader
     * @param WysiwygImageHelper $wysiwygImageHelper
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ImageUploader $imageUploader,
        WysiwygImageHelper $wysiwygImageHelper,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->imageUploader = $imageUploader;
        $this->wysiwygImageHelper = $wysiwygImageHelper;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
    }

    /**
     * Process Request Poster
     *
     * @param RequestInterface $request
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(RequestInterface $request): string
    {
        $resourcePathPoster = $request->getParam('resource_path_poster_image');
        $tmpFile= $resourcePathPoster[0]['tmp_name'] ?? null;
        $imageName = $resourcePathPoster[0]['name'] ?? null;
        $imageUrl = $resourcePathPoster[0]['url'] ?? null;
        
        if ($tmpFile && $imageName) {
            return $this->imageUploader->getBasePath() . '/' . $this->imageUploader->moveFileFromTmp($imageName);
        } elseif ($imageUrl) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $ds = DIRECTORY_SEPARATOR;
            $wysiwygMediaRoot = trim($this->wysiwygImageHelper->getStorageRoot(), $ds);
            $pubPath = trim($this->filesystem->getDirectoryRead(DirectoryList::PUB)->getAbsolutePath(), $ds);
            $mediaPath = $this->replaceRegex($pubPath, $wysiwygMediaRoot) . $ds;
            $mediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $imageUrl = $this->replaceRegex(
                $mediaUrl,
                $this->replaceRegex(
                    $mediaPath,
                    $imageUrl
                )
            );
            return $imageUrl;
        } else {
            return '';
        }
    }

    /**
     * Return Process Request
     *
     * @param string $pattern
     * @param string $subject
     * @param string $replacement
     * @return string
     */
    protected function replaceRegex($pattern, $subject, $replacement = '')
    {
        return preg_replace('/^' . preg_quote($pattern, '/') . '/', $replacement, $subject);
    }
}
