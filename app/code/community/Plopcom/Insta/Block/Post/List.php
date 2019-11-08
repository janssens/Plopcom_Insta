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
 * Post list block
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author G Janssens
 */
class Plopcom_Insta_Block_Post_List extends Mage_Core_Block_Template
{

    const CACHE_KEY_PREFIX = 'PLOPCOM_INSTA_BLOCK_';

    private $_posts;


    /**
     * initialize
     *
     * @access public
     * @author G Janssens
     */
    public function _construct()
    {
        parent::_construct();
        $this->_posts = Mage::getResourceModel('plopcom_insta/post_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1)
            ->setOrder('timestamp', 'desc');
    }

    public function getPosts($limit = 3){
        $this->_posts->getSelect()->limit($limit);
        return $this->_posts;
    }

    public function getCacheKey(){
        return self::CACHE_KEY_PREFIX.'UID'.Mage::app()->getStore()->getCode();
    }

    public function getCacheLifetime(){
        return 60*60*24; //24h
    }

    public function getCacheTags(){
        $tags = array();
        foreach ($this->getPosts() as $post){
            $tags[] = $post->getCacheTag();
        }
        return $tags;
    }
}
