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
 * Post resource model
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Model_Resource_Post extends Mage_Core_Model_Resource_Db_Abstract
{

    //'entity_id'
    //'media_id'
    //'media_url'
    //'like_count'
    //'timestamp'
    //'username'
    //'short'
    //'caption'
    //'status'
    //'updated_at'
    //'created_at'

    /**
     * constructor
     *
     * @access public
     * @author G Janssens
     */
    public function _construct()
    {
        $this->_init('plopcom_insta/post', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $postId
     * @return array
     * @author G Janssens
     */
    public function lookupStoreIds($postId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('plopcom_insta/post_store'), 'store_id')
            ->where('post_id = ?', (int)$postId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Plopcom_Insta_Model_Resource_Post
     * @author G Janssens
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Plopcom_Insta_Model_Post $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('insta_post_store' => $this->getTable('plopcom_insta/post_store')),
                $this->getMainTable() . '.entity_id = insta_post_store.post_id',
                array()
            )
            ->where('insta_post_store.store_id IN (?)', $storeIds)
            ->order('insta_post_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign post to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Plopcom_Insta_Model_Resource_Post
     * @author G Janssens
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('plopcom_insta/post_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'post_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'post_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
