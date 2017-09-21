<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* e107 Blank Plugin
*
*/

if (!defined('e107_INIT')) {
    require_once ("../../class2.php");
}
e107::lan('addressbook',false, true);
 

require_once ('includes/addressbook_class.php');
$addressbookObj = new addressbook_class;
$render = $addressbookObj->runPage();

if ($render) {
    require_once (HEADERF); // render the header (everything before the main content area)

     e107::getRender()->tablerender(LAN_PLUGIN_ADDRESSBOOK_FRONT_NAME, $addressbookObj->text);

    require_once (FOOTERF); // render the footer (everything after the main content area)

}
