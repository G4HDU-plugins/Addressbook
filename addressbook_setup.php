<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for blank plugin
**
*/


if (!class_exists("addressbook_setup")) {
    class addressbook_setup
    {

        function install_pre($var)
        {
            // print_a($var);
            // echo "custom install 'pre' function<br /><br />";
        }

        /**
         * For inserting default database content during install after table has been created by the blank_sql.php file.
         */
        function install_post($var)
        {
            $sql = e107::getDb();
            $mes = e107::getMessage();
            /**
             * 
             * Insert countries into table
             * 
             */
            $countries = "
            INSERT INTO `#addressbook_countries` (`addressbook_countries_id`, `addressbook_countries_name`) VALUES
	('AD', 'Andorra'),
	('AE', 'United Arab Emirates'),
	('AF', 'Afghanistan'),
	('AG', 'Antigua and Barbuda'),
	('AI', 'Anguilla'),
	('AL', 'Albania'),
	('AM', 'Armenia'),
	('AO', 'Angola'),
	('AQ', 'Antarctica'),
	('AR', 'Argentina'),
	('AS', 'American Samoa'),
	('AT', 'Austria'),
	('AU', 'Australia'),
	('AW', 'Aruba'),
	('AX', 'Åland Islands'),
	('AZ', 'Azerbaijan'),
	('BA', 'Bosnia and Herzegovina'),
	('BB', 'Barbados'),
	('BD', 'Bangladesh'),
	('BE', 'Belgium'),
	('BF', 'Burkina Faso'),
	('BG', 'Bulgaria'),
	('BH', 'Bahrain'),
	('BI', 'Burundi'),
	('BJ', 'Benin'),
	('BL', 'Saint Barthélemy'),
	('BM', 'Bermuda'),
	('BN', 'Brunei Darussalam'),
	('BO', 'Bolivia'),
	('BQ', 'Caribbean Netherlands '),
	('BR', 'Brazil'),
	('BS', 'Bahamas'),
	('BT', 'Bhutan'),
	('BV', 'Bouvet Island'),
	('BW', 'Botswana'),
	('BY', 'Belarus'),
	('BZ', 'Belize'),
	('CA', 'Canada'),
	('CC', 'Cocos (Keeling) Islands'),
	('CD', 'Congo, Democratic Republic of'),
	('CF', 'Central African Republic'),
	('CG', 'Congo'),
	('CH', 'Switzerland'),
	('CI', 'Côte d\'Ivoire'),
	('CK', 'Cook Islands'),
	('CL', 'Chile'),
	('CM', 'Cameroon'),
	('CN', 'China'),
	('CO', 'Colombia'),
	('CR', 'Costa Rica'),
	('CU', 'Cuba'),
	('CV', 'Cape Verde'),
	('CW', 'Curaçao'),
	('CX', 'Christmas Island'),
	('CY', 'Cyprus'),
	('CZ', 'Czech Republic'),
	('DE', 'Germany'),
	('DJ', 'Djibouti'),
	('DK', 'Denmark'),
	('DM', 'Dominica'),
	('DO', 'Dominican Republic'),
	('DZ', 'Algeria'),
	('EC', 'Ecuador'),
	('EE', 'Estonia'),
	('EG', 'Egypt'),
	('EH', 'Western Sahara'),
	('ER', 'Eritrea'),
	('ES', 'Spain'),
	('ET', 'Ethiopia'),
	('FI', 'Finland'),
	('FJ', 'Fiji'),
	('FK', 'Falkland Islands'),
	('FM', 'Micronesia, Federated States of'),
	('FO', 'Faroe Islands'),
	('FR', 'France'),
	('GA', 'Gabon'),
	('GB', 'United Kingdom'),
	('GD', 'Grenada'),
	('GE', 'Georgia'),
	('GF', 'French Guiana'),
	('GG', 'Guernsey'),
	('GH', 'Ghana'),
	('GI', 'Gibraltar'),
	('GL', 'Greenland'),
	('GM', 'Gambia'),
	('GN', 'Guinea'),
	('GP', 'Guadeloupe'),
	('GQ', 'Equatorial Guinea'),
	('GR', 'Greece'),
	('GS', 'South Georgia and the South Sandwich Islands'),
	('GT', 'Guatemala'),
	('GU', 'Guam'),
	('GW', 'Guinea-Bissau'),
	('GY', 'Guyana'),
	('HK', 'Hong Kong'),
	('HM', 'Heard and McDonald Islands'),
	('HN', 'Honduras'),
	('HR', 'Croatia'),
	('HT', 'Haiti'),
	('HU', 'Hungary'),
	('ID', 'Indonesia'),
	('IE', 'Ireland'),
	('IL', 'Israel'),
	('IM', 'Isle of Man'),
	('IN', 'India'),
	('IO', 'British Indian Ocean Territory'),
	('IQ', 'Iraq'),
	('IR', 'Iran'),
	('IS', 'Iceland'),
	('IT', 'Italy'),
	('JE', 'Jersey'),
	('JM', 'Jamaica'),
	('JO', 'Jordan'),
	('JP', 'Japan'),
	('KE', 'Kenya'),
	('KG', 'Kyrgyzstan'),
	('KH', 'Cambodia'),
	('KI', 'Kiribati'),
	('KM', 'Comoros'),
	('KN', 'Saint Kitts and Nevis'),
	('KP', 'North Korea'),
	('KR', 'South Korea'),
	('KW', 'Kuwait'),
	('KY', 'Cayman Islands'),
	('KZ', 'Kazakhstan'),
	('LA', 'Lao People\'s Democratic Republic'),
	('LB', 'Lebanon'),
	('LC', 'Saint Lucia'),
	('LI', 'Liechtenstein'),
	('LK', 'Sri Lanka'),
	('LR', 'Liberia'),
	('LS', 'Lesotho'),
	('LT', 'Lithuania'),
	('LU', 'Luxembourg'),
	('LV', 'Latvia'),
	('LY', 'Libya'),
	('MA', 'Morocco'),
	('MC', 'Monaco'),
	('MD', 'Moldova'),
	('ME', 'Montenegro'),
	('MF', 'Saint-Martin (France)'),
	('MG', 'Madagascar'),
	('MH', 'Marshall Islands'),
	('MK', 'Macedonia'),
	('ML', 'Mali'),
	('MM', 'Myanmar'),
	('MN', 'Mongolia'),
	('MO', 'Macau'),
	('MP', 'Northern Mariana Islands'),
	('MQ', 'Martinique'),
	('MR', 'Mauritania'),
	('MS', 'Montserrat'),
	('MT', 'Malta'),
	('MU', 'Mauritius'),
	('MV', 'Maldives'),
	('MW', 'Malawi'),
	('MX', 'Mexico'),
	('MY', 'Malaysia'),
	('MZ', 'Mozambique'),
	('NA', 'Namibia'),
	('NC', 'New Caledonia'),
	('NE', 'Niger'),
	('NF', 'Norfolk Island'),
	('NG', 'Nigeria'),
	('NI', 'Nicaragua'),
	('NL', 'The Netherlands'),
	('NO', 'Norway'),
	('NP', 'Nepal'),
	('NR', 'Nauru'),
	('NU', 'Niue'),
	('NZ', 'New Zealand'),
	('OM', 'Oman'),
	('PA', 'Panama'),
	('PE', 'Peru'),
	('PF', 'French Polynesia'),
	('PG', 'Papua New Guinea'),
	('PH', 'Philippines'),
	('PK', 'Pakistan'),
	('PL', 'Poland'),
	('PM', 'St. Pierre and Miquelon'),
	('PN', 'Pitcairn'),
	('PR', 'Puerto Rico'),
	('PS', 'Palestine, State of'),
	('PT', 'Portugal'),
	('PW', 'Palau'),
	('PY', 'Paraguay'),
	('QA', 'Qatar'),
	('RE', 'Réunion'),
	('RO', 'Romania'),
	('RS', 'Serbia'),
	('RU', 'Russian Federation'),
	('RW', 'Rwanda'),
	('SA', 'Saudi Arabia'),
	('SB', 'Solomon Islands'),
	('SC', 'Seychelles'),
	('SD', 'Sudan'),
	('SE', 'Sweden'),
	('SG', 'Singapore'),
	('SH', 'Saint Helena'),
	('SI', 'Slovenia'),
	('SJ', 'Svalbard and Jan Mayen Islands'),
	('SK', 'Slovakia'),
	('SL', 'Sierra Leone'),
	('SM', 'San Marino'),
	('SN', 'Senegal'),
	('SO', 'Somalia'),
	('SR', 'Suriname'),
	('SS', 'South Sudan'),
	('ST', 'Sao Tome and Principe'),
	('SV', 'El Salvador'),
	('SX', 'Sint Maarten (Dutch part)'),
	('SY', 'Syria'),
	('SZ', 'Swaziland'),
	('TC', 'Turks and Caicos Islands'),
	('TD', 'Chad'),
	('TF', 'French Southern Territories'),
	('TG', 'Togo'),
	('TH', 'Thailand'),
	('TJ', 'Tajikistan'),
	('TK', 'Tokelau'),
	('TL', 'Timor-Leste'),
	('TM', 'Turkmenistan'),
	('TN', 'Tunisia'),
	('TO', 'Tonga'),
	('TR', 'Turkey'),
	('TT', 'Trinidad and Tobago'),
	('TV', 'Tuvalu'),
	('TW', 'Taiwan'),
	('TZ', 'Tanzania'),
	('UA', 'Ukraine'),
	('UG', 'Uganda'),
	('UM', 'United States Minor Outlying Islands'),
	('US', 'United States'),
	('UY', 'Uruguay'),
	('UZ', 'Uzbekistan'),
	('VA', 'Vatican'),
	('VC', 'Saint Vincent and the Grenadines'),
	('VE', 'Venezuela'),
	('VG', 'Virgin Islands (British)'),
	('VI', 'Virgin Islands (U.S.)'),
	('VN', 'Vietnam'),
	('VU', 'Vanuatu'),
	('WF', 'Wallis and Futuna Islands'),
	('WS', 'Samoa'),
	('YE', 'Yemen'),
	('YT', 'Mayotte'),
	('ZA', 'South Africa'),
	('ZM', 'Zambia'),
	('ZW', 'Zimbabwe');";
            if ($sql->gen($countries)) {
                $mes->add("Countries added", E_MESSAGE_SUCCESS);
            } else {
                $mes->add("Failed to add contries list", E_MESSAGE_ERROR);
            }

            /**
             * 
             * Insert titles into table
             * 
             */
            $titles = "
INSERT INTO `#addressbook_titles` (`addressbook_titles_id`, `addressbook_titles_title`) VALUES
	(1, 'Mr'),
	(2, 'Mrs'),
	(3, 'Miss'),
	(4, 'Ms'),
	(5, 'Mx'),
	(6, 'Revd'),
	(7, 'Dr'),
	(8, 'Sir'),
	(9, 'Lady'),
	(10, 'Fr'),
	(11, 'Sr'),
	(12, 'Lord'),
	(13, 'Prof');";
            if ($sql->gen($titles)) {
                $mes->add("Titles added", E_MESSAGE_SUCCESS);
            } else {
                $mes->add("Failed to add titles list", E_MESSAGE_ERROR);
            }

            /**
             * 
             * Insert roles into table
             * 
             */

            $roles = "
            INSERT INTO `#addressbook_roles` (`addressbook_roles_id`, `addressbook_roles_role`) VALUES
	(1, 'None'),
	(2, 'President'),
	(3, 'V. President'),
	(4, 'Secretary'),
    (5, 'Treasurer'),
    (6, 'Webmaster'),
    (7, 'Social Sec'),
    (8, 'Almoner')
    ;";
            if ($sql->gen($roles,true)) {
                $mes->add("Roles added", E_MESSAGE_SUCCESS);
            } else {
                $mes->add("Failed to add roles", E_MESSAGE_ERROR);
            }
        //die("ww2");
           /**
             * 
             * Insert cats into table
             * 
             */

            $cats = "
            INSERT INTO `#addressbook_categories` (`addressbook_categories_id`, `addressbook_categories_name`) VALUES
	(1, 'None'),
	(2, 'Club Member'),
	(3, 'Other Club Member'),
    (4, 'Zone Officer'),
    (5, 'Division Officer');";
            if ($sql->gen($cats)) {
                $mes->add("Categories added", E_MESSAGE_SUCCESS);
            } else {
                $mes->add("Failed to add category", E_MESSAGE_ERROR);
            }
        }

        function uninstall_options()
        {
            return;

        }


        function uninstall_post($var)
        {

        }

        function upgrade_post($var)
        {

        }

    }

}
