<?php

namespace Auraine\BannerSlider\Controller\Adminhtml\Slider;

use Auraine\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Auraine_BannerSlider::slider';

    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param SliderRepositoryInterface $sliderRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        SliderRepositoryInterface $sliderRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->sliderRepository = $sliderRepository;
        $this->dataPersistor = $dataPersistor;
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
        $sliderId = $this->getRequest()->getParam('entity_id');
        $sliderData = $this->getRequest()->getPostValue();

        try {
            $model = $sliderId ? $this->sliderRepository->loadById($sliderId) : $this->sliderRepository->create();
            $this->populateModelWithData($model, [
                'title',
                'is_show_title',
                'is_enabled',
                'additional_information',
                'link',
                'product_ids',
                'additional_information',
                'discover',
                'product_banner',
                'identifier',
                'slider_type',
                'page_type',
                'sort_order',
                'target_type',
                'target_id',
                'category_id',
                'display_type'
                
            ]);
            $this->dataPersistor->set('bannerslider_slider', $model->getData());
            $model = $this->sliderRepository->save($model);
            $this->dataPersistor->clear('bannerslider_slider');
            $this->messageManager->addSuccessMessage(__('Slider %1 saved successfully', $model->getEntityId()));
            
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
