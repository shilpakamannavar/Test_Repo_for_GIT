<?php
namespace Auraine\Brands\Block\Adminhtml\Brands\Edit;

use Magento\Store\Model\System\Store;
/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Auraine\Brands\Model\Status $options,
        \Auraine\Brands\Model\FeatureStatus $feature_options,
        Store $systemStore,
        array $data = []
    ) {
        $this->_options = $options;
        $this->_feature_options = $feature_options;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->systemStore = $systemStore;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
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


        // $fieldset->addType(
        //     'categories',
        //     '\Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category'
        //    );

        //    $fieldset->addField(
        //     'categories_id',
        //     'categories',
        //     [
        //         'name' => 'categories_id',
        //         'label' => __('Categories'),
        //         'title' => __('Categories'),
        //         'class' => 'required-entry admin__action-multiselect-wrap action-select-wrap admin__action-multiselect-tree',
        //     ]
        // );
        
        // $fieldset->addField(
        //     'urlkey',
        //     'text',
        //     [
        //         'name' => 'urlkey',
        //         'label' => __('URL Key'),
        //         'id' => 'urlkey',
        //         'title' => __('URL Key'),
        //         'class' => 'required-entry',
        //         'required' => true,
        //     ]
        // );

      
        $field = $fieldset->addField(
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

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'style' => 'height:5em;',
                'required' => true,
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
                'values' => $this->_feature_options->getOptionArrayYesNo(),
              
               
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
                'values' => $this->_feature_options->getOptionArrayYesNo(),
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
                'values' => $this->_feature_options->getOptionArrayYesNo(),
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
                'values' => $this->_feature_options->getOptionArrayYesNo(),
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
                'values' => $this->_options->getOptionArray(),
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
                'class' => 'required-entry',
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

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}