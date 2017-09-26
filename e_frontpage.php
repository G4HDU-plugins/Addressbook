<?php

/*
* Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/
/**
 * e_frontpage used to enable the addressbook to be an web opening page.
 *
 * @copyright Barry Keal G4HDU 2017
 *
 * 
 */

if (!defined('e107_INIT'))
{
    exit;
}


/**
 * addressbook_frontpage
 * 
 * @package Address Book
 * @author Father Barry
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class addressbook_frontpage // include plugin-folder in the name.
{
    /**
     * addressbook_frontpage::config()
     * 
     * @return
     */
    function config()
    {
        $frontPage = array('page' => '{e_PLUGIN}addressbook/index.php', 'title' => LAN_PLUGIN_ADDRESSBOOK_NAME);
        return $frontPage;
    }

}

?>