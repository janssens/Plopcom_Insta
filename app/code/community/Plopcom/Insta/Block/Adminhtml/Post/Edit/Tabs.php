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
 * Post admin edit tabs
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author G Janssens
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('post_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('plopcom_insta')->__('Post'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Edit_Tabs
     * @author G Janssens
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_post',
            array(
                'label'   => Mage::helper('plopcom_insta')->__('Post'),
                'title'   => Mage::helper('plopcom_insta')->__('Post'),
                'content' => $this->getLayout()->createBlock(
                    'plopcom_insta/adminhtml_post_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_post',
                array(
                    'label'   => Mage::helper('plopcom_insta')->__('Store views'),
                    'title'   => Mage::helper('plopcom_insta')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'plopcom_insta/adminhtml_post_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve post entity
     *
     * @access public
     * @return Plopcom_Insta_Model_Post
     * @author G Janssens
     */
    public function getPost()
    {
        return Mage::registry('current_post');
    }
}
