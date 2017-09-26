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
    exit;
}

// v2.x Standard  - Simple mod-rewrite module.

class addressbook_url // plugin-folder + '_url'
{
    function config()
    {
        $config = array();

        $config['index'] = array(
            'alias' => 'addressbook',
            'regex' => '^{alias}\/?(.*)$', // matched against url, and if true, redirected to 'redirect' below.
            'sef' => '{alias}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}addressbook/index.php?$1', // file-path of what to load when the regex returns true.

            );
        return $config;
    }


}
