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
 * Post admin grid block
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author G Janssens
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('postGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Grid
     * @author G Janssens
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('plopcom_insta/post')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Grid
     * @author G Janssens
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('plopcom_insta')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'media_id',
            array(
                'header'    => Mage::helper('plopcom_insta')->__('Media Id'),
                'align'     => 'left',
                'index'     => 'media_id',
            )
        );

        $this->addColumn(
            'media_url',
            array(
                'header'    => Mage::helper('plopcom_insta')->__('Media'),
                'index'     => 'media_url',
                'renderer' => 'Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Media',
                'filter' => false
            )
        );

        $this->addColumn(
            'like_count',
            array(
                'header'    => Mage::helper('plopcom_insta')->__('Like count'),
                'align'     => 'left',
                'index'     => 'like_count'
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('plopcom_insta')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('plopcom_insta')->__('Enabled'),
                    '0' => Mage::helper('plopcom_insta')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'username',
            array(
                'header' => Mage::helper('plopcom_insta')->__('username'),
                'index'  => 'username',
                'renderer' => 'Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Username',
            )
        );
        $this->addColumn(
            'caption',
            array(
                'header' => Mage::helper('plopcom_insta')->__('Caption'),
                'index'  => 'caption',
                'renderer' => 'Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Caption',
                'filter' => false
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('plopcom_insta')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('plopcom_insta')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('plopcom_insta')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('plopcom_insta')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('plopcom_insta')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('plopcom_insta')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('plopcom_insta')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('plopcom_insta')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Grid
     * @author G Janssens
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('post');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('plopcom_insta')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('plopcom_insta')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('plopcom_insta')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('plopcom_insta')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('plopcom_insta')->__('Enabled'),
                            '0' => Mage::helper('plopcom_insta')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'store_add',
            array(
                'label'      => Mage::helper('plopcom_insta')->__('Add store'),
                'url'        => $this->getUrl('*/*/massStoreAdd', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'store_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('plopcom_insta')->__('Store'),
                        'values' => $this->storesToArray()
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'store_remove',
            array(
                'label'      => Mage::helper('plopcom_insta')->__('Remove store'),
                'url'        => $this->getUrl('*/*/massStoreRemove', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'store_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('plopcom_insta')->__('Store'),
                        'values' => $this->storesToArray()
                    )
                )
            )
        );
        return $this;
    }

    protected function storesToArray(){
        $stores_array = array();
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $stores_array[$store->getId()] = $store->getName();
                }
            }
        }
        return $stores_array;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Plopcom_Insta_Model_Post
     * @return string
     * @author G Janssens
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author G Janssens
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Plopcom_Insta_Block_Adminhtml_Post_Grid
     * @author G Janssens
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Plopcom_Insta_Model_Resource_Post_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Plopcom_Insta_Block_Adminhtml_Post_Grid
     * @author G Janssens
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
