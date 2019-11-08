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
 * Post model
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Model_Post extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plopcom_insta_post';
    const CACHE_TAG_PREFIX = 'PLOPCOM_INSTA_BLOCK_';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'plopcom_insta_post';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'post';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('plopcom_insta/post');
    }

    public function getLink()
    {
        return 'https://www.instagram.com/p/'.$this->getShort().'/';
    }

    public function getAlt()
    {
        $re = '/#\S+\s*/';
        return preg_replace($re, '', $this->getCaption());
    }

    public function addStore($store_id)
    {
        $stores = Mage::getResourceModel('plopcom_insta/post')->lookupStoreIds($this->getId());
        if (!is_array($stores)){
            $stores = array($stores);
        }
        $stores_after = array_merge($stores,array($store_id));
        $this->setStores(array_unique($stores_after));

        return $this;
    }

    public function removeStore($store_id)
    {
        $stores = Mage::getResourceModel('plopcom_insta/post')->lookupStoreIds($this->getId());
        if (!is_array($stores)){
            $stores = array($stores);
        }
        $stores_after = array_diff($stores,array($store_id));
        $this->setStores(array_unique($stores_after));
        return $this;
    }

    public function getMediaUrl(){
        $path = Mage::helper('plopcom_insta')->getImagePath($this->getMediaId());
        if (file_exists($path)){
            return Mage::helper('plopcom_insta')->getImageUrl($this->getMediaId());;
        }else{
            return $this->getData('media_url');
        }
    }

    public function getCaption(){
        $text = preg_replace('/(#[\w\d]*)/','',base64_decode($this->getData('caption')));
        $text = preg_replace('/([\s\n\t\r\.][\s\n\t\r]+)/',' ',$text);
        return htmlspecialchars(trim($text), ENT_QUOTES);
    }

    /**
     * before save post
     *
     * @access protected
     * @return Plopcom_Insta_Model_Post
     * @author G Janssens
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    public function getCacheTag(){
        return self::CACHE_TAG_PREFIX.$this->getId();
    }

    /**
     * save post relation
     *
     * @access public
     * @return Plopcom_Insta_Model_Post
     * @author G Janssens
     */
    protected function _afterSave()
    {
        Mage::getSingleton('core/cache')->clean(array($this->getCacheTag()));
        return parent::_afterSave();
    }
    
}
