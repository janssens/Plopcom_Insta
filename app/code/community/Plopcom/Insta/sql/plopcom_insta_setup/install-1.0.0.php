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
 * Insta module install script
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
$this->startSetup();

$table = $this->getTable('plopcom_insta/post');
$sql=<<<SQLTEXT
DROP TABLE IF EXISTS {$table} ;
SQLTEXT;
$this->run($sql);

$table = $this->getConnection()
    ->newTable($table)
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Post ID'
    )
    ->addColumn(
        'media_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Media Id'
    )
    ->addColumn(
        'username',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Username'
    )
    ->addColumn(
        'caption',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Caption'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Creation Time'
    ) 
    ->setComment('Post Table');
$this->getConnection()->createTable($table);


$table = $this->getTable('plopcom_insta/post_store');
$sql=<<<SQLTEXT
DROP TABLE IF EXISTS {$table} ;
SQLTEXT;
$this->run($sql);

$table = $this->getConnection()
    ->newTable($table)
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Post ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'plopcom_insta/post_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'plopcom_insta/post_store',
            'post_id',
            'plopcom_insta/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('plopcom_insta/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'plopcom_insta/post_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Posts To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
