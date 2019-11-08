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
 * store selection tab
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post_Edit_Tab_Stores extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Edit_Tab_Stores
     * @author G Janssens
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('post');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'post_stores_form',
            array('legend' => Mage::helper('plopcom_insta')->__('Store views'))
        );
        $field = $fieldset->addField(
            'store_id',
            'multiselect',
            array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('plopcom_insta')->__('Store Views'),
                'title'    => Mage::helper('plopcom_insta')->__('Store Views'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            )
        );
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);
        $form->addValues(Mage::registry('current_post')->getData());
        return parent::_prepareForm();
    }
}
