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

    $this->run("ALTER TABLE $tableName ADD UNIQUE (media_id);");

}
$installer->endSetup();

