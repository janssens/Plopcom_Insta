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
 * Post admin edit form
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'plopcom_insta';
        $this->_controller = 'adminhtml_post';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('plopcom_insta')->__('Save Post')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('plopcom_insta')->__('Delete Post')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('plopcom_insta')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author G Janssens
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_post') && Mage::registry('current_post')->getId()) {
            return Mage::helper('plopcom_insta')->__(
                "Edit Post '%s'",
                $this->escapeHtml(Mage::registry('current_post')->getMediaId())
            );
        } else {
            return Mage::helper('plopcom_insta')->__('Add Post');
        }
    }
}
