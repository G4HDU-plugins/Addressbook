<?php

/*
* Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/

if (!defined('e107_INIT'))
{
    require_once ("../../class2.php");
}
e107::lan('addressbook', false, true); // load English front


require_once ('includes/addressbook_class.php');
$addressbookObj = new addressbook_class;
$render = $addressbookObj->runPage();

if ($render)
{
    require_once (HEADERF); // render the header (everything before the main content area)
    e107::getRender()->tablerender(LAN_PLUGIN_ADDRESSBOOK_FRONT_NAME, $addressbookObj->text);
    require_once (FOOTERF); // render the footer (everything after the main content area)
}
