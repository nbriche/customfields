<?php

if (!defined('GLPI_ROOT')) {
   die('Sorry. You can\'t access this file directly.');
}

class PluginCustomfieldsDropdownsItem extends CommonTreeDropdown  {

   static function canView() {
      return true;
   }

   static function canCreate() {
      return true;
   }

   static function getFormURL($full=true) {
      global $CFG_GLPI;

      $dir = ($full ? $CFG_GLPI['root_doc'] : '');
      $dir .= "/plugins/customfields";
      $get = "";

      if (isset($_GET['popup'])) {
         $get = "?popup=".$_GET['popup'];
      }

      return "$dir/front/dropdownsitem.form.php$get";
   }

   function defineTabs($options=array()) {
      global $LANG;

      return array();
   }

   static function item_empty(CommonDBTM $item) {
      if (isset($_REQUEST['name'])) {
         $item->fields['name'] = $_REQUEST['name'];
      }
   }

   function displaySpecificTypeField($ID, $field=array()) {

      switch ($field['type']) {
         case "plugin_customfields_dropdowns_id";
            if (isset($_REQUEST['plugin_customfields_dropdowns_id'])) {
               $ID = $_REQUEST['plugin_customfields_dropdowns_id'];
            } elseif (isset($this->fields[$field['name']])) {
               $ID = $this->fields[$field['name']];
            } else $ID = -1;


            Dropdown::show(getItemTypeForTable(getTableNameForForeignKeyField($field['name'])),
                              array('value'        => $ID,
                                    'name'         => $field['name'],
                                    'entity'       => $this->getEntityID(),
                                    'auto_submit'  => true));
            break;
         case "plugin_customfields_dropdownsitems_id";
            $condition = "plugin_customfields_dropdowns_id = -1";
            if (isset($_REQUEST['plugin_customfields_dropdowns_id'])) {
               $condition = "plugin_customfields_dropdowns_id = '"
                                       .$_REQUEST['plugin_customfields_dropdowns_id']."'";
            }
            if ($field['name']=='entities_id') {
               $restrict = -1;
            } else {
               $restrict = $this->getEntityID();
            }
            Dropdown::show(getItemTypeForTable($this->getTable()),
                           array('value'     => $this->fields[$field['name']],
                                 'name'      => $field['name'],
                                 'comments'  => false,
                                 'entity'    => $restrict,
                                 'used'      => ($ID>0 ? getSonsOf($this->getTable(), $ID)
                                                    : array()),
                                 'condition' => $condition));
            break;
      }
   }

   static function getTypeName($nb=0) {
      global $LANG;

      return $LANG['plugin_customfields']['Custom_Dropdown'];
   }

   function getAdditionalFields() {
      global $LANG;

      $fields = array();
      $fields[] = array('name'  => 'plugin_customfields_dropdowns_id',
                         'label' => $LANG['plugin_customfields']['Custom_Dropdown'],
                         'type'  => 'plugin_customfields_dropdowns_id',
                         'list'  => false);
      $fields[] = array('name'  => 'plugin_customfields_dropdownsitems_id',
                         'label' => __('As child of'),
                         'type'  => 'plugin_customfields_dropdownsitems_id',
                         'list'  => false);

      return $fields;

   }

   function getSearchOptions() {
      global $LANG;

      $tab = parent::getSearchOptions();

      $tab[3]['table'] = 'glpi_plugin_customfields_dropdownsitems';
      $tab[3]['field'] = 'plugin_customfields_dropdowns_id';
      $tab[3]['name']  = $LANG['plugin_customfields']['Custom_Dropdown'];

      $tab[4]['table'] = 'glpi_plugin_customfields_dropdownsitems';
      $tab[4]['field'] = 'plugin_customfields_dropdownsitems_id';
      $tab[4]['name']  = __('As child of');

      return $tab;
   }

   function prepareInputForAdd($input) {
      global $LANG;

      // Check mandatory
      $mandatory_ok = true;

      if (!isset($input["plugin_customfields_dropdowns_id"])
         || empty($input["plugin_customfields_dropdowns_id"])) {
         Session::addMessageAfterRedirect($LANG['plugin_customfields']['error'][1], false, ERROR);
         $mandatory_ok = false;
      }

      if (!$mandatory_ok) {
         return false;
      }

      return parent::prepareInputForAdd($input);
   }
}

?>
