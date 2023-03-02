<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Banner;

use Auraine\BannerSlider\Api\BannerRepositoryInterface;
use Auraine\BannerSlider\Model\Banner\ResourcePath\ProcessorPool;
use Auraine\BannerSlider\Model\Banner\ResourcePathMobile\ProcessorPoolMobile;
use Auraine\BannerSlider\Model\Banner\ResourcePathPoster\ProcessorPoolPoster;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Auraine_BannerSlider::banner';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;
    /**
     * @var ProcessorPool
     */
    private $processorPool;
    /**
     * @var ProcessorPoolMobile
     */
    private $processorPoolMobile;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param BannerRepositoryInterface $bannerRepository
     * @param DataPersistorInterface $dataPersistor
     * @param ProcessorPool $processorPool
     * @param ProcessorPoolMobile $processorPoolMobile
     */
    public function __construct(
        Action\Context $context,
        BannerRepositoryInterface $bannerRepository,
        DataPersistorInterface $dataPersistor,
        ProcessorPool $processorPool,
        ProcessorPoolMobile $processorPoolMobile,
        ProcessorPoolposter $processorPoolPoster
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->bannerRepository = $bannerRepository;
        $this->processorPool = $processorPool;
        $this->processorPoolMobile = $processorPoolMobile;
        $this->processorPoolPoster = $processorPoolPoster;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $sliderData = $this->getRequest()->getPostValue();
        try {
            if ($id) {
                $model = $this->bannerRepository->loadById($id);
            } else {
                $model = $this->bannerRepository->create();
            }
            $this->populateModelWithData($model, [
                'slider_id',
                'title',
                'resource_map_id',
                'resource_type',
                'alt_text',
                'link',
                'sort_order',
                'additional_information',
                'is_enabled',
                'slider_target_id',
                'target_type',
                'video_type',
                'target_id',
                'category_id'
            ]);
            $model['slider_target_id'] = ($sliderData['slider_target_id']) ?
                implode(",", $sliderData['slider_target_id']) : '';
            $resourcePathProcessors = $this->processorPool->getProcessors();
            if (isset($resourcePathProcessors[$model->getResourceType()])) {
                try {
                    $model->setResourcePath($resourcePathProcessors[
                    $model->getResourceType()
                    ]->process($this->getRequest()));
                } catch (LocalizedException $e) {
                    $this->dataPersistor->set('bannerslider_banner', $model->getData());
                    throw new CouldNotSaveException(__($e->getMessage()));
                }
            }

            $resourcePathProcessorsMobile = $this->processorPoolMobile->getProcessors();
            if (isset($resourcePathProcessorsMobile[$model->getResourceType()])) {
                try {
                    $model->setResourcePathMobile($resourcePathProcessorsMobile[
                    $model->getResourceType()
                    ]->process($this->getRequest()));
                } catch (LocalizedException $e) {
                    $this->dataPersistor->set('bannerslider_banner', $model->getData());
                    throw new CouldNotSaveException(__($e->getMessage()));
                }
            }

            $resourcePathProcessorsPoster = $this->processorPoolPoster->getProcessors();
            
            if (isset($resourcePathProcessorsPoster[$model->getResourceType()])) {

                try {
                    $model->setResourcePathPoster($resourcePathProcessorsPoster[
                    $model->getResourceType()
                    ]->process($this->getRequest()));
                } catch (LocalizedException $e) {
                    $this->dataPersistor->set('bannerslider_banner', $model->getData());
                    throw new CouldNotSaveException(__($e->getMessage()));
                }
            }
            $this->dataPersistor->set('bannerslider_banner', $model->getData());
            $model = $this->bannerRepository->save($model);

            $this->dataPersistor->clear('bannerslider_banner');
            $this->messageManager->addSuccessMessage(__('Banner %1 saved successfully', $model->getEntityId()));
            switch ($this->getRequest()->getParam('back')) {
                case 'continue':
                    $url = $this->getUrl('*/*/edit', ['entity_id' => $model->getEntityId()]);
                    break;
                case 'close':
                    $url = $this->getUrl('*/*');
                    break;
                default:
                    $url = $this->getUrl('*/*');
            }
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $url = $this->getUrl('*/*');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $url = $this->getUrl('*/*/edit', ['entity_id' => $id]);
        }
        return $this->resultRedirectFactory->create()->setUrl($url);
    }

    /**
     * Get Model Data
     *
     * @param \Magento\Framework\DataObject $model
     * @param string[] $fields
     */
    protected function populateModelWithData($model, $fields)
    {
        foreach ($fields as $field) {
            $model->setData($field, $this->getRequest()->getParam($field));
        }
    }
}
