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
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('plopcom_insta/post');
// Check if the table already exists
if ($installer->getConnection()->isTableExists($tableName)) {
    $table = $installer->getConnection();

    $installer->getConnection()
        ->addColumn($tableName,'like_count', array(
            'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'nullable'  => true,
            'after'     => 'media_url', // column name to insert new column after
            'comment'   => 'Like count'
        ));

}
$installer->endSetup();

