<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Application test case for BagIt plugin.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package omeka
 * @subpackage BagIt
 * @author Scholars' Lab
 * @author David McClure (david.mcclure@virginia.edu)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 * PHP version 5
 *
 */
?>

<?php

require_once '../BagItPlugin.php';

class BagIt_Test_AppTestCase extends Omeka_Test_AppTestCase
{

    private $_dbHelper;

    public function setUp()
    {

        parent::setUp();

        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        // Then set up BagIt.
        $bagit_plugin_broker = get_plugin_broker();
        $this->_addBagItPluginHooksAndFilters($bagit_plugin_broker, 'BagIt');
        $bagit_plugin_helper = new Omeka_Test_Helper_Plugin;
        $bagit_plugin_helper->setUp('BagIt');

    }

    public function _addBagItPluginHooksAndFilters($plugin_broker, $plugin_name)
    {
        $plugin_broker->setCurrentPluginDirName($plugin_name);
        new BagItPlugin;
    }

    public function _createFileCollection($name)
    {

        $collection = new BagitFileCollection;
        $collection->name = $name;
        $collection->save();

        return $collection;

    }

    public function _createFileCollections($number)
    {

        $collections = array();
        for ($i=1; $i < ($number+1); $i++) {
            $collections[] = $this->_createFileCollection('Testing Collection ' . $i);
        }

        return $collections;

    }

    public function _createItem($name)
    {

        $item = new Item;
        $item->featured = 0;
        $item->public = 1;
        $item->save();

        $element_text = new ElementText;
        $element_text->record_id = $item->id;
        $element_text->record_type = "Item";
        $element_text->element_id = 50;
        $element_text->html = 0;
        $element_text->text = $name;
        $element_text->save();

    }

    public function _createFiles()
    {

        $src = '_files';
        $handle = opendir(BAGIT_TESTS_DIRECTORY . '/' . $src);
        $i = 1;
        while (false !== ($file = readdir($handle))) {

            if (($file != '.') && ($file != '..') && ($file != '.DS_Store')) {

                $db = get_db();
                $sql = 'INSERT INTO omeka_files 
                    (item_id, size, has_derivative_image, filename, original_filename, metadata) 
                    VALUES (1, 5000, 0, "' . $file . '", "TestFile' . $i . '.jpg", "")';
                $db->query($sql);
                $i++;

            }

        }

    }

    public function _createTestBagForRead($bag_name)
    {

        copy(BAGIT_TESTS_DIRECTORY . '/' . $bag_name, BAGIT_PLUGIN_DIRECTORY . '/bagtmp/' . $bag_name);

    }

    public function _clearDirectory($dir_name)
    {

        $handle = opendir($dir_name);
        while (false !== ($file = readdir($handle))) {

            if (is_file($dir_name . '/' . $file) && $file !== '.gitignore') {
                unlink($dir_name . '/' . $file);
            } elseif (is_dir($dir_name . '/' . $file) && $file !== '.' && $file !== '..') {
                $this->_clearDirectory($dir_name . '/' . $file);
                rmdir($dir_name . '/' . $file);
            }

        }

    }

    public function _clearDbTable($table_name)
    {

        $db = get_db();
        $db->query('TRUNCATE TABLE ' . $db->prefix . $table_name);

    }

}
