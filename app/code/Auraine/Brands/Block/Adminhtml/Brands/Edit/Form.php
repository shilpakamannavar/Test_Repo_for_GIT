<?php
namespace Auraine\Brands\Block\Adminhtml\Brands\Edit;

use Magento\Store\Model\System\Store;
use Auraine\BannerSlider\Model\Config\Source\Slider;
use Auraine\Brands\Block\Adminhtml\Brands\BrandSlider;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $options;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $featureOptions;
     /**
      * @var \Magento\Store\Model\System\Store
      */
    protected $wysiwygConfig;
     /**
      * @var \Magento\Store\Model\System\Store
      */
    protected $slider;
     /**
      * @var \Magento\Store\Model\System\Store
      */
    protected $brandSlider;
    /**
     * Constructor function
     *
     * @param Context $context,Registry $registry,FormFactory $formFactory,Config $wysiwygConfig
     *
     * @param Status $options,FeatureStatus $featureOptions,Store $systemStore,array $data
     *
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Auraine\Brands\Model\Status $options,
        \Auraine\Brands\Model\FeatureStatus $featureOptions,
        Slider $slider,
        Store $systemStore,
        BrandSlider $brandSlider,
        array $data = []
    ) {
        $this->options = $options;
        $this->featureOptions = $featureOptions;
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->slider = $slider;
       
        $this->systemStore = $systemStore;
        $this->brandSlider = $brandSlider;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );
        $form->setHtmlIdPrefix('wkgrid_');
        if ($model->getId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Brand Information'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add New Brands'), 'class' => 'fieldset-wide']
            );
        }

        $sliderOptions=$this->brandSlider->getSliderOptions();
        $sliderOptions=array_merge([['value' => '', 'label' => __('Select Slider')]], $sliderOptions);
        
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Brand Name'),
                'id' => 'title',
                'title' => __('Brand Name'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );
      
        $fieldset->addField(
            'stores',
            'select',
            [
                'label' => __('Store View'),
                'title' => __('Store View'),
                'name' => 'stores',
                'value' => $model->getStoreId(),
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
            //                set first argument true and second to false to add blank option which value is blank
            //                set second argument true to add "All Store Views" option which value is 0
            ]
        );

        $wysiwygConfig = $this->wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'style' => 'height:5em;',
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Brand Logo'),
                'id' => 'image',
                'title' => __('Brand Logo'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'is_popular',
            'select',
            [
                'name' => 'is_popular',
                'label' => __('Is Popular'),
                'id' => 'is_popular',
                'title' => __('Is Popular'),
                'values' => $this->featureOptions->getOptionArrayYesNo(),
            ]
        );
        $fieldset->addField(
            'is_featured',
            'select',
            [
                'name' => 'is_featured',
                'label' => __('Is Featured'),
                'id' => 'is_featured',
                'title' => __('Is Featured'),
                'values' => $this->featureOptions->getOptionArrayYesNo(),
                'class' => 'switch-checkbox',
               
            ]
        );

        $fieldset->addField(
            'is_exclusive',
            'select',
            [
                'name' => 'is_exclusive',
                'label' => __('Is Exclusive'),
                'id' => 'is_exclusive',
                'title' => __('Is Exclusive'),
                'values' => $this->featureOptions->getOptionArrayYesNo(),
                'class' => 'switch-checkbox',
               
            ]
        );

        $fieldset->addField(
            'is_justin',
            'select',
            [
                'name' => 'is_justin',
                'label' => __('Is Just Launched'),
                'id' => 'is_justin',
                'title' => __('Is Just Launched'),
                'values' => $this->featureOptions->getOptionArrayYesNo(),
                'class' => 'toggle',
              
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'id' => 'status',
                'title' => __('Status'),
                'values' => $this->options->getOptionArray(),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
                'name' => 'url_key',
                'label' => __('URL Key'),
                'id' => 'url_key',
                'title' => __('URL Key'),
                'class' => 'validate-alphanum required-entry',
                'required' => true,
                'note' => "URL key for website redirect"
            ]
        );
        $fieldset->addField(
            'meta_key',
            'text',
            [
                'name' => 'meta_key',
                'label' => __('Meta Key'),
                'id' => 'meta_key',
                'title' => __('Meta Key'),
                'required' => false,
                'note' => "For SEO purpose"
              
            ]
        );
        $fieldset->addField(
            'meta_description',
            'textarea',
            [
                'name' => 'meta_description',
                'label' => __('Meta Description'),
                'id' => 'meta_description',
                'title' => __('Meta Description'),
                'required' => false,
                'note' => "For SEO purpose"
            ]
        );
        $fieldset->addField(
            'brand_banner_slider_id',
            'select',
            [
                'name' => 'brand_banner_slider_id',
                'label' => __('Banner Slider'),
                'id' => 'brand_banner_slider_id',
                'title' => __('Banner Slider'),
                'required' => false,
                'values' => $sliderOptions,
                'note' => "For Landing Page Top Banner"
            ]
        );
        $fieldset->addField(
            'brand_offer_slider_id',
            'select',
            [
                'name' => 'brand_offer_slider_id',
                'label' => __('Banner Slider'),
                'id' => 'brand_offer_slider_id',
                'title' => __('Offer Slider'),
                'required' => false,
                'values' => $sliderOptions,
                'note' => "For Landing Page Offer Banner"
            ]
        );
        $fieldset->addField(
            'brand_exclusive_top_slider_id',
            'select',
            [
                'name' => 'brand_exclusive_top_slider_id',
                'label' => __('Exclusive Slider'),
                'id' => 'brand_exclusive_top_slider_id',
                'title' => __('Exclusive Top Slider'),
                'required' => false,
                'values' => $sliderOptions,
                'note' => "For Landing Page Exclusive Top slider"
            ]
        );
        $fieldset->addField(
            'brand_exclusive_banner_slider_id',
            'select',
            [
                'name' => 'brand_exclusive_banner_slider_id',
                'label' => __('Exclusive Slider'),
                'id' => 'brand_exclusive_banner_slider_id',
                'title' => __('Exclusive Banners Slider'),
                'required' => false,
                'values' => $sliderOptions,
                'note' => "For Landing Page Exclusive Banners slider"
            ]
        );
        $fieldset->addField(
            'brand_blogs_slider_id',
            'select',
            [
                'name' => 'brand_blogs_slider_id',
                'label' => __('Blogs Slider'),
                'id' => 'brand_blogs_slider_id',
                'title' => __('Exclusive Blogs Slider'),
                'required' => false,
                'values' => $sliderOptions,
                'note' => "For Landing Page Blogs slider"
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
