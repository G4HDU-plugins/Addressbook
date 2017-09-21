<?php
/*
* e107 website system
*
* Copyright (C) 2008-2014 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
* 
* blank e_search addon 
*/



if (!defined('e107_INIT')) {
    exit;
}

// v2.x e_search addon.


class addressbook_search extends e_search // include plugin-folder in the name.
{

    function config()
    {
        $search = array(
            'name' => "Address Book",
            'table' => 'addressbook_entries  
                left join #addressbook_roles ON addressbook_role = addressbook_roles_id
                left join #addressbook_titles ON addressbook_title = addressbook_titles_id
                left join #addressbook_categories ON addressbook_category = addressbook_categories_id
                left join #addressbook_countries ON addressbook_country = addressbook_countries_id',

            'advanced' => array('date' => array('type' => 'date', 'text' => LAN_DATE_POSTED),
            'author' => array('type' => 'author', 'text' => LAN_SEARCH_61)),

            'return_fields' => array(
                'addressbook_id',
                'addressbook_lastname',
                'addressbook_firstname',
                'addressbook_lastupdate',
                'addressbook_city',
                'addressbook_phone',
                'addressbook_mobile',
                'addressbook_email1',
                'addressbook_roles_role',
                'addressbook_titles_title',
                'addressbook_countries_name',
                'addressbook_categories_name',
                ),
            'search_fields' => array(
                'addressbook_lastname' => '2.0',
                'addressbook_firstname' => '1.7',
                'addressbook_email1' => '1.6',
                'addressbook_city' => '1.3',
                'addressbook_categories_name' => '1.3',
                'addressbook_roles_role' => '1.2',
                'addressbook_countries_name' => '1.0'), // fields and weights.

            'order' => array('addressbook_lastname' => 'DESC', 'addressbook_firstname' =>
                    'ASC'),
            'refpage' => 'index.php');
        $class = e107::pref('addressbook', 'viewClass');
      //  if (e107::getUser()->checkClass($class, true)) {
            return $search;
       // }
    }


    /* Compile Database data for output */
    function compile($row)
    {
        $tp = e107::getParser();
        $class = e107::pref('addressbook', 'viewClass');

        // preg_match("/([0-9]+)\.(.*)/", $row['blank_nick'], $user);
       // if (e107::getUser()->checkClass($class, false)) {
            $res = array();

            $res['link'] = e_PLUGIN . "addressbook/index.php?action=view&id=" . $row['addressbook_id'];
            $res['pre_title'] = 'Entry : ';
            $res['title'] = $row['addressbook_titles_title'] . ' ' . $row['addressbook_lastname'] .
                ', ' . $row['addressbook_firstname'];

            $res['summary'] = $row['addressbook_roles_role'] . ' ' . $row['addressbook_city'] .
                ' ' . $row['addressbook_phone'] . ' ' . $row['addressbook_mobile'] . ' ' . $row['addressbook_email1'] .
                ' ' . $row['addressbook_categories_name'] . ' ';
            $res['detail'] = $tp->toDate($row['addressbook_lastupdate'], "long");
       // } else {
        //    $res = array();

       // }
        return $res;

    }


    /**
     * Optional - Advanced Where
     * @param $parm - data returned from $_GET (ie. advanced fields included. in this case 'date' and 'author' )
     */
    function where($parm = '')
    {
        $tp = e107::getParser();

        $qry = "";

        if (vartrue($parm['time']) && is_numeric($parm['time'])) {
            $qry .= " blank_datestamp " . ($parm['on'] == 'new' ? '>=' : '<=') . " '" . (time
                () - $parm['time']) . "' AND";
        }

        if (vartrue($parm['author'])) {
            $qry .= " blank_nick LIKE '%" . $tp->toDB($parm['author']) . "%' AND";
        }

        return $qry;
    }


}
