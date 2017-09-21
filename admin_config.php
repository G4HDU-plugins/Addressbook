<?php
/*
* e107 website system
*
* Copyright (C) 2017 Barry Keal G4HDU
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* e107 Address Bbook Plugin
*
* $Source: 
* $Revision$
* $Date$
* $Author$ Barry Keal G4HDU
*
*/

require_once ("../../class2.php");
if (!getperms("P")) {
    e107::redirect('admin');
    exit;
}

/**
 * plugin_addressbook_admin
 * 
 * @package   
 * @author Address Book
 * @copyright Barry Keal G4HDU
 * @version 1.1.1
 * @access public
 */
class plugin_addressbook_admin extends e_admin_dispatcher
{
    /**
     * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
     * Note - default mode/action is autodetected in this order:
     * - $defaultMode/$defaultAction (owned by dispatcher - see below)
     * - $adminMenu (first key if admin menu array is not empty)
     * - $modes (first key == mode, corresponding 'index' key == action)
     * @var array
     */
    protected $modes = array(
        'main' => array(
            'controller' => 'plugin_addressbook_admin_ui',
            'path' => null,
            'ui' => 'plugin_addressbook_admin_form_ui',
            'uipath' => null),
        'titles' => array(
            'controller' => 'plugin_addressbook_titles_ui',
            'path' => null,
            'ui' => 'plugin_addressbook_admin_titles_form_ui',
            'uipath' => null),
        'roles' => array(
            'controller' => 'plugin_addressbook_roles_ui',
            'path' => null,
            'ui' => 'plugin_addressbook_admin_roles_form_ui',
            'uipath' => null),
        'categories' => array(
            'controller' => 'plugin_addressbook_categories_ui',
            'path' => null,
            'ui' => 'plugin_addressbook_admin_categories_form_ui',
            'uipath' => null),
        'countries' => array(
            'controller' => 'plugin_addressbook_countries_ui',
            'path' => null,
            'ui' => 'plugin_addressbook_admin_countries_form_ui',
            'uipath' => null),


        );


    /**
     * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}blank/admin_config.php', 'perm' => '0']);
     * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
     * @var array
     */
    protected $adminMenu = array(

        'main/prefs' => array('caption' => 'Settings', 'perm' => '0'),
        'other4' => array('divider' => true),
        'main/list' => array('caption' => 'Manage Entries', 'perm' => '0'),
        'main/create' => array('caption' => LAN_CREATE, 'perm' => '0'),
        'other0' => array('divider' => true),
        'titles/list' => array('caption' => 'Manage Titles', 'perm' => '0'),
        'titles/create' => array('caption' => LAN_CREATE, 'perm' => '0'),
        'other1' => array('divider' => true),
        'roles/list' => array('caption' => 'Manage Roles', 'perm' => '0'),
        'roles/create' => array('caption' => LAN_CREATE, 'perm' => '0'),
        'other2' => array('divider' => true),
        'categories/list' => array('caption' => 'Manage Categories', 'perm' => '0'),
        'categories/create' => array('caption' => LAN_CREATE, 'perm' => '0'),
        'other3' => array('divider' => true),
        'countries/list' => array('caption' => 'Manage Countries', 'perm' => '0'),
        'countries/create' => array('caption' => LAN_CREATE, 'perm' => '0'),


        //'main/custom' => array('caption' => 'Custom Page', 'perm' => '0')
        );

    /**
     * Optional, mode/action aliases, related with 'selected' menu CSS class
     * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
     * This will mark active main/list menu item, when current page is main/edit
     * @var array
     */
    protected $adminMenuAliases = array('main/edit' => 'main/list');

    /**
     * Navigation menu title
     * @var string
     */
    protected $menuTitle = 'Address Book Menu';
}


/**
 * plugin_addressbook_admin_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_admin_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = "Address Book";

    /**
     * plugin name or 'core'
     * IMPORTANT: should be 'core' for non-plugin areas because this
     * value defines what CONFIG will be used. However, I think this should be changed
     * very soon (awaiting discussion with Cam)
     * Maybe we need something like $prefs['core'], $prefs['blank'] ... multiple getConfig support?
     *
     * @var string
     */
    protected $pluginName = 'addressbook';

    /**
     * DB Table, table alias is supported
     * Example: 'r.blank'
     * @var string
     */
    protected $table = "addressbook_entries";

    /**
     * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
     * Write your list query without any Order or Limit.
     *
     * @var string [optional]
     */
    protected $listQry = "";
    //

    // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    //protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

    // required - if no custom model is set in init() (primary id)
    protected $pid = "addressbook_id";

    // optional
    protected $perPage = 20;

    protected $batchDelete = true;
    /**
     * 			
     * @var array
     */

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),
        'addressbook_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => false,
            'primary' => true /*, 'noedit'=>TRUE*/ ), //Primary ID is not editable
        'addressbook_title' => array(
            'title' => 'Title',
            'type' => 'method',
            'data' => 'int',
            'width' => '15%',
            'thclass' => '',
            'nolist' => true,
            'filter' => false,
            //   'writeParms' =>  $this->titles,
            'forced' => false),
        'addressbook_lastname' => array(
            'title' => 'Last Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),
        'addressbook_firstname' => array(
            'title' => 'First Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),
        'addressbook_addr1' => array(
            'title' => 'Address 1',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'forced' => true,
            'thclass' => ''),
        'addressbook_addr2' => array(
            'title' => 'Address 2',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),
        'addressbook_city' => array(
            'title' => 'Town/City',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),
        'addressbook_county' => array(
            'title' => 'County/State',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),

        'addressbook_country' => array(
            'title' => 'Country',
            'type' => 'method',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),

        'addressbook_postcode' => array(
            'title' => 'Post/Zip Code',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),
        'addressbook_phone' => array(
            'title' => 'Phone',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => ''),
        'addressbook_mobile' => array(
            'title' => 'Mobile/Cell',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => ''),
        'addressbook_email1' => array(
            'title' => 'Email',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => ''),
        'addressbook_email2' => array(
            'title' => 'Alt Email',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),
        'addressbook_website' => array(
            'title' => "Website",
            'type' => 'url',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => 'left'),

        'addressbook_category' => array(
            'title' => 'Category',
            'type' => 'method',
            'data' => 'int',
            'width' => '15%',
            'thclass' => '',
            //   'writeParms' =>  $this->titles,
            'forced' => true),

        'addressbook_role' => array(
            'title' => 'Role',
            'type' => 'method',
            'data' => 'int',
            'width' => '15%',
            'nolist' => false,
            'thclass' => '',
            'filter' => false,
            'forced' => true),
            
        'addressbook_comments' => array(
            'title' => 'Additional Information',
            'type' => 'textarea',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => true,
            'thclass' => ''),
        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    protected $fieldpref = array();

    protected $prefs = array(
        'viewClass' => array(
            'title' => 'View Class',
            'type' => 'userclass',
            'data' => 'string',
            'validate' => false),

        'perPage' => array(
            'title' => 'Per Page',
            'type' => 'number',
            'data' => 'integer'),

        'defaultcountry' => array(
            'title' => 'Default Country',
            'type' => 'method',
            'data' => 'str'),
        );


    /**
     * plugin_addressbook_admin_ui::init()
     * 
     * @return void
     */
    public function init()
    {

    }
}
/**
 * plugin_addressbook_admin_form_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_admin_form_ui extends e_admin_form_ui
{

    /**
     * plugin_addressbook_admin_form_ui::addressbook_title()
     * 
     * @param mixed $curVal
     * @param mixed $mode
     * @return
     */
    function addressbook_title($curVal, $mode)
    {

        $sql = e107::getDb();
        $sql->select('addressbook_titles',
            'addressbook_titles_id,addressbook_titles_title', '', 'nowhere');
        while ($row = $sql->fetch('assoc')) {

            $this->titles[$row['addressbook_titles_id']] = $row['addressbook_titles_title'];
        }
        //  print_a($this->titles);


        $types = $this->titles;

        if ($mode == 'read') {
            return vartrue($types[$curVal]) . '';
        }

        if ($mode == 'batch') // Custom Batch List for blank_type
            {
            return $types;
        }

        if ($mode == 'filter') // Custom Filter List for blank_type
            {
            return $types;
        }
        $frm = e107::getForm();
        return $frm->select('addressbook_title', $types, $curVal);
    }

    /**
     * plugin_addressbook_admin_form_ui::addressbook_category()
     * 
     * @param mixed $curVal
     * @param mixed $mode
     * @return
     */
    function addressbook_category($curVal, $mode)
    {
         $sql = e107::getDb();
        $sql->select('addressbook_categories', 'addressbook_categories_id,addressbook_categories_name',
            '', 'nowhere');
        while ($row = $sql->fetch('assoc')) {

            $this->roles[$row['addressbook_categories_id']] = $row['addressbook_categories_name'];
        }


        $types = $this->roles;

        if ($mode == 'read') {
            return vartrue($types[$curVal]) . '';
        }

        if ($mode == 'batch') // Custom Batch List for blank_type
            {
            return $types;
        }

        if ($mode == 'filter') // Custom Filter List for blank_type
            {
            return $types;
        }
        //     print_a($this->titles);
        $frm = e107::getForm();
        return $frm->select('addressbook_category', $types, $curVal);
    }
    /**
     * plugin_addressbook_admin_form_ui::addressbook_role()
     * 
     * @param mixed $curVal
     * @param mixed $mode
     * @return
     */
    function addressbook_role($curVal, $mode)
    {
        $sql = e107::getDb();
        $sql->select('addressbook_roles', 'addressbook_roles_id,addressbook_roles_role',
            '', 'nowhere');
        while ($row = $sql->fetch('assoc')) {

            $this->roles[$row['addressbook_roles_id']] = $row['addressbook_roles_role'];
        }


        $types = $this->roles;

        if ($mode == 'read') {
            return vartrue($types[$curVal]) . '';
        }

        if ($mode == 'batch') // Custom Batch List for blank_type
            {
            return $types;
        }

        if ($mode == 'filter') // Custom Filter List for blank_type
            {
            return $types;
        }
        //     print_a($this->titles);
        $frm = e107::getForm();
        return $frm->select('addressbook_role', $types, $curVal);
    }

    /**
     * plugin_addressbook_admin_form_ui::addressbook_country()
     * 
     * @param mixed $curVal
     * @param mixed $mode
     * @return
     */
    function addressbook_country($curVal='', $mode)
    {
        if(empty($curVal)){
        $curVal = e107::pref('addressbook', 'defaultcountry');
        
        }
        $sql = e107::getDb();
        $sql->select('addressbook_countries',
            'addressbook_countries_id,addressbook_countries_name',
            'ORDER BY addressbook_countries_name', 'nowhere');
        while ($row = $sql->fetch('assoc')) {

            $this->countries[$row['addressbook_countries_id']] = $row['addressbook_countries_name'];
        }

        $types = $this->countries;

        if ($mode == 'read') {
            return vartrue($types[$curVal]) . '';
        }

        if ($mode == 'batch') // Custom Batch List for blank_type
            {
            return $types;
        }

        if ($mode == 'filter') // Custom Filter List for blank_type
            {
            return $types;
        }
        //     print_a($this->titles);
        $frm = e107::getForm();
        return $frm->select('addressbook_country', $types, $curVal);
    }

    /**
     * plugin_addressbook_admin_form_ui::defaultcountry()
     * 
     * @param mixed $curVal
     * @param mixed $mode
     * @return
     */
    function defaultcountry($curVal, $mode)
    {
        $sql = e107::getDb();
        $sql->select('addressbook_countries',
            'addressbook_countries_id,addressbook_countries_name',
            'ORDER BY addressbook_countries_name', 'nowhere');
        while ($row = $sql->fetch('assoc')) {

            $this->countries[$row['addressbook_countries_id']] = $row['addressbook_countries_name'];
        }

        $types = $this->countries;

        if ($mode == 'read') {
            return vartrue($types[$curVal]) . '';
        }

        if ($mode == 'batch') // Custom Batch List for blank_type
            {
            return $types;
        }

        if ($mode == 'filter') // Custom Filter List for blank_type
            {
            return $types;
        }
        //     print_a($this->titles);
        $frm = e107::getForm();
        return $frm->select('defaultcountry', $types, $curVal);
    }
}

/**
 * plugin_addressbook_titles_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_titles_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = "Address Book";

    protected $pluginName = 'addressbook';

    protected $table = "addressbook_titles";

    protected $listQry = "";
    protected $pid = "addressbook_titles_id";
    protected $perPage = 20;

    protected $batchDelete = true;

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),

        'addressbook_titles_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => false,
            'primary' => true /*, 'noedit'=>TRUE*/ ), //Primary ID is not editable

        'addressbook_titles_title' => array(
            'title' => 'Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),

        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    /**
     * plugin_addressbook_titles_ui::init()
     * 
     * @return
     */
    public function init()
    {

    }
}
/**
 * plugin_addressbook_countries_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_countries_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = "Address Book";

    protected $pluginName = 'addressbook';

    protected $table = "addressbook_countries";

    protected $listQry = "";
    protected $pid = "addressbook_countries_id";
    protected $perPage = 20;

    protected $batchDelete = true;

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),

        'addressbook_countries_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'str',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => false,
            'primary' => true /*, 'noedit'=>TRUE*/ ), //Primary ID is not editable

        'addressbook_countries_name' => array(
            'title' => 'Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),

        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    /**
     * plugin_addressbook_countries_ui::init()
     * 
     * @return
     */
    public function init()
    {

    }
}


/**
 * plugin_addressbook_roles_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_roles_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = "Address Book";


    protected $pluginName = 'addressbook';


    protected $table = "addressbook_roles";

    protected $listQry = "";
    protected $pid = "addressbook_roles_id";

    protected $perPage = 20;

    protected $batchDelete = true;

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),
        'addressbook_roles_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => false,
            'primary' => true /*, 'noedit'=>TRUE*/ ), //Primary ID is not editable


        'addressbook_roles_role' => array(
            'title' => 'Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),

        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    // optional
    /**
     * plugin_addressbook_roles_ui::init()
     * 
     * @return
     */
    public function init()
    {

    }
}
/**
 * plugin_addressbook_admin_roles_form_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_admin_roles_form_ui extends e_admin_form_ui
{
}

/**
 * plugin_addressbook_cats_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_categories_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = "Address Book";

    protected $pluginName = 'addressbook';

    protected $table = "addressbook_categories";

    protected $listQry = "";
    protected $pid = "addressbook_categories_id";

    protected $perPage = 20;

    protected $batchDelete = true;

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),

        'addressbook_categories_id' => array(
            'title' => LAN_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => false,
            'primary' => true /*, 'noedit'=>TRUE*/ ), //Primary ID is not editable

        'addressbook_categories_name' => array(
            'title' => 'Name',
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'nolist' => false,
            'forced' => true,
            'thclass' => '',
            'batch' => true,
            'filter' => true),

        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    // optional
    /**
     * plugin_addressbook_cats_ui::init()
     * 
     * @return
     */
    public function init()
    {

    }
}
/**
 * plugin_addressbook_admin_cats_form_ui
 * 
 * @package   
 * @author Address Book
 * @copyright Father Barry
 * @version 2017
 * @access public
 */
class plugin_addressbook_admin_categories_form_ui extends e_admin_form_ui
{

}

new plugin_addressbook_admin();

require_once (e_ADMIN . "auth.php");

e107::getAdminUI()->runPage();

require_once (e_ADMIN . "footer.php");
