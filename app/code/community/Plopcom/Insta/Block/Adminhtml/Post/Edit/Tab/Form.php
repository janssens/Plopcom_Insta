<?php
/**
 * Plopcom_Insta extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Plopcom
 * @package        Plopcom_Insta
 * @copyright      Copyright (c) 2019
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Post edit form tab
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'post_form',
            array('legend' => Mage::helper('plopcom_insta')->__('Post'))
        );

        $fieldset->addField(
            'media_id',
            'text',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Media Id'),
                'name'  => 'media_id',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'username',
            'text',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Username'),
                'name'  => 'username',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'caption',
            'textarea',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Caption'),
                'name'  => 'caption',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'timestamp',
            'text',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Timestamp'),
                'name'  => 'timestamp',
                'required'  => true,
                'class' => 'required-entry',

            )
        );

        $fieldset->addField(
            'short',
            'text',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Short'),
                'name'  => 'short',
                'required'  => true,
                'class' => 'required-entry',

            )
        );

        $fieldset->addField(
            'like_count',
            'text',
            array(
                'label' => Mage::helper('plopcom_insta')->__('Like count'),
                'name'  => 'like_count',
                'required'  => true,
                'class' => 'required-entry',

            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('plopcom_insta')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('plopcom_insta')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('plopcom_insta')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_post')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_post')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getPostData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getPostData());
            Mage::getSingleton('adminhtml/session')->setPostData(null);
        } elseif (Mage::registry('current_post')) {
            $formValues = array_merge($formValues, Mage::registry('current_post')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
