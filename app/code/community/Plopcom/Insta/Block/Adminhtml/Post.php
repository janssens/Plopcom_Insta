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
 * Post admin block
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_post';
        $this->_blockGroup         = 'plopcom_insta';
        parent::__construct();
        $this->_headerText         = Mage::helper('plopcom_insta')->__('Post');
        $this->_updateButton('add', 'label', Mage::helper('plopcom_insta')->__('Add Post'));

        $message = Mage::helper('plopcom_insta')->__('Are you sure you want to load last posts form instagram ?');
        $this->addButton('load_last_posts', array(
            'label'     => Mage::helper('plopcom_insta')->__('Load last Posts'),
            'onclick'   => "confirmSetLocation('{$message}', '{$this->getUrl('*/*/loadLastPosts')}')",
            'class'     => 'go' //not really good icon
        ));
    }
}
