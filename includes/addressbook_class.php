<?php

/*
* Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/
class addressbook_class
{
    private $db;
    private $tp;
    private $frm;
    public $ns;
    private $template;
    private $message;
    private $shortcodes;
    private $prefs;
    private $allowedActions;
    private $rolesValue = 0;
    private $search = '';
    private $pdfInstalled = false;
    private $session = false;

    function __construct()
    {
        error_reporting(E_ALL);
        $this->pdfInstalled = e107::isInstalled('e107pdf');
        //  var_dump($this->pdfInstalled);
        if ($this->pdfInstalled)
        {
            require_once (e_PLUGIN . 'pdf/e107pdf.php'); //require the e107pdf class
        }
        $this->message = e107::getMessage();

        e107::js('footer', e_PLUGIN . 'addressbook/js/addressbook.js', 'jquery'); // Load Plugin javascript and include jQuery framework
        e107::css('addressbook', e_PLUGIN . 'addressbook/css/addressbook.css'); // load css file
        //  e107::lan('addressbook'); // load language file ie. e107_plugins/addressbook/languages/English.php

        require_once (e_PLUGIN . 'addressbook/templates/addressbook_template.php');
        $this->template = new addressbookTemplate;

        require_once (e_PLUGIN . 'addressbook/shortcodes/addressbook_shortcodes.php');
        $this->shortcodes = new addressbookShortcodes;

        $this->db = e107::getDB(); // mysql class object
        $this->tp = e107::getParser(); // parser for converting to HTML and parsing templates etc.
        $this->frm = e107::getForm(); // Form element class.
        $this->ns = e107::getRender(); // render in theme box.
        $this->prefs = e107::pref('addressbook'); // returns an array.
        $this->allowedActions = array(
            'ajaxview',
            'view',
            'csv',
            'pdf',
            'list',
            '');
        //print_a($pref);
        $this->session = new e_session();
        $this->session->init('addressbook');
        //var_dump( $this->session->getNamespaceKey());
        //var_dump( $this->session->getData('addbook'));

        $this->session->setData('addbook', 'fred');

    }
    /**
     * addressbook_class::runPage()
     * 
     * @return
     */
    public function runPage()
    {
        $class = $this->prefs['viewClass'];
        if (!e107::getUser()->checkClass($class, false))
        {
            $this->text = $this->notPermitted();
            $this->render = true;
        } else
        {
            $text = '';
            $this->action = $_GET['action'];
            if (!in_array($this->action, $this->allowedActions))
            {
                die('Fail');
            }
            // check commands in allowed array
            $this->id = intval($_GET['id']);
            $this->from = intval($_GET['from']);
            $this->rolesValue = intval($_GET['roles']);
            $this->search = $_GET['search'];
            $render = false;
            switch ($this->action)
            {
                case 'ajaxview':
                    //  sleep(10);
                    $this->ajaxPage($this->id);
                    $this->render = false;
                    break;
                case 'view':
                    $this->text = $this->viewPage($this->id);
                    $this->render = true;
                    break;
                case 'csv':
                    $this->generateCSV();
                    break;
                case 'pdf':
                    $this->generatePDF();
                    break;
                case 'list':
                default:
                    $this->text = $this->viewList();
                    $this->render = true;
                    break;
            }
        }
        return $this->render;
    }

    /**
     * addressbook_class::notPermitted()
     * 
     * @return
     */
    private function notPermitted()
    {
        return $this->tp->parseTemplate($this->template->notPermitted($row), true);
    }
    /**
     * addressbook_class::createWhere()
     * 
     * @return void
     */
    private function createWhere()
    {
        $lastSearch = $this->session->getData('searchterm');

        $lastRole = $this->session->getData('roleterm');

        $whereSearch = '';

        if (strlen($this->search) !== 0)
        {
            $this->session->setData('searchterm', $this->search);
            $whereSearch = " (addressbook_firstname like '%{$this->search}%' OR addressbook_lastname like '%{$this->search}%') ";
        } else
        {
            $this->session->setData('searchterm', '');
        }

        $roleWhere = '';
        if ($this->rolesValue > 0)
        {
            $this->session->setData('roleterm', $this->rolesValue);
            // $_SESSION['roleterm'] = $this->rolesValue;
            $roleWhere = '  addressbook_role=' . $this->rolesValue;
        } else
        {
            $this->session->setData('searchterm', 0);

            //  search or role has changed. go back to start of list

        }

        if ($lastSearch != $this->search || $lastRole != $this->rolesValue)
        {
            $this->from = 0;
        }

        $where = '';
        // print $whereSearch.' '.$roleWhere;
        if (!empty($whereSearch) && empty($roleWhere))
        {
            $where = " WHERE " . $whereSearch;
        } elseif (empty($whereSearch) && !empty($roleWhere))
        {
            $where = " WHERE " . $roleWhere;
        } elseif (!empty($whereSearch) && !empty($roleWhere))
        {
            $where = " WHERE " . $whereSearch . ' AND ' . $roleWhere;
        }
        // var_dump($where);
        return $where;
    }
    /**
     * addressbook_class::generateCSV()
     * 
     * @return void
     */
    private function generateCSV()
    {

        $where = $this->createWhere();

        $text = '';
        //   var_dump($where);
        $text .= $this->tp->parseTemplate($this->template->addressbookListHeader(), true);
        $qry = 'SELECT *
        FROM #addressbook_entries 
        LEFT JOIN #addressbook_roles ON addressbook_role=addressbook_roles_id
        LEFT JOIN #addressbook_titles ON addressbook_title=addressbook_titles_id
        LEFT JOIN #addressbook_categories ON addressbook_category=addressbook_categories_id
        LEFT JOIN #addressbook_countries ON addressbook_country=addressbook_countries_id
        ' . $where . ' 
        ORDER BY addressbook_lastname,addressbook_firstname';
        $numrows = $this->db->gen($qry, false);
        $file_name = 'addressbook.csv';
        // var_dump($this->shortcodes);
        # output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        # Disable caching - HTTP 1.1
        header("Cache-Control: no-cache, no-store, must-revalidate");
        # Disable caching - HTTP 1.0
        header("Pragma: no-cache");
        # Disable caching - Proxies
        header("Expires: 0");
        $output = fopen("php://output", "w");
        $data = array(
            'Title',

            'Last Name',
            'First Name',
            'Address 1',
            'Address 2',
            'Town',
            'County',
            'Country',
            'Post Code',
            'Phone',
            'Mobile',
            'Email',
            'Alternate Email',
            'Website',
            'Role',
            'Comments');
        fputcsv($output, $data);
        if ($numrows == 0)
        {
            // $text .= $this->tp->parseTemplate($this->template->addressbookListNone(), true);
        } else
        {
            while ($row = $this->db->fetch())
            {
                $data[0] = $row['addressbook_titles_title'];
                $data[1] = $row['addressbook_lastname'];
                $data[2] = $row['addressbook_firstname'];
                $data[3] = $row['addressbook_addr1'];

                $data[4] = $row['addressbook_addr2'];
                $data[5] = $row['addressbook_city'];
                $data[6] = $row['addressbook_county'];
                $data[7] = $row['addressbook_countries_name'];
                $data[8] = $row['addressbook_postcode'];
                $data[9] = $row['addressbook_phone'];
                $data[10] = $row['addressbook_mobile'];
                $data[11] = $row['addressbook_email1'];
                $data[12] = $row['addressbook_email2'];
                $data[13] = $row['addressbook_website'];
                $data[14] = $row['addressbook_roles_role'];
                $data[15] = $row['addressbook_comments'];
                fputcsv($output, $data);
            }

        }
        fclose($output);
        $this->action = 'list';
    }
    function generatePDF()
    {

        $pdf = new ABPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        //   $pdf->watermark('Confidential',array(100,100));
        //   		$text		= $text;					//define text
        $creator = SITENAME; //define creator
        $author = 'baz'; //define author
        $title = 'addressbook'; //define title
        $subject = $category_name; //define subject
        $keywords = ''; //define keywords
        $image_file = e_PLUGIN . 'addressbook/images/logo.png';
        define('PDFLOGO', $image_file);
        $pdf->Image($image_file, 0, 0, '48', '48', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // $orientation='P',$unit='mm',$format='A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false
        $text = array(
            $text,
            $creator,
            $author,
            $title,
            $subject,
            $keywords,
            $url);
        // Simple watermark
        //$pdf->watermark();
        $pdf->SetMargins(10, 35, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->AddPage();
        $header = array(
            'Title',
            'Name',
            'Phone',
            'Mobile',
            'Email',
            'Address',
            '',
            'Town',
            'Role');
        // 24 rows per table

        $qry = 'SELECT *
        FROM #addressbook_entries 
        LEFT JOIN #addressbook_roles ON addressbook_role=addressbook_roles_id
        LEFT JOIN #addressbook_titles ON addressbook_title=addressbook_titles_id
        ' . $where . ' 
        ORDER BY addressbook_lastname,addressbook_firstname';
        $numrows = $this->db->gen($qry, false);
        $rowCount = 0;
        $totalRows = 0;
        while ($row = $this->db->fetch())
        {
            $data[] = array(
                $row['addressbook_title'],
                $row['addressbook_lastname'] . ', ' . $row['addressbook_firstname'],
                $row['addressbook_addr1'],
                $row['addressbook_addr2'],
                $row['addressbook_city'],
                $row['addressbook_phone'],
                $row['addressbook_mobile'],
                $row['addressbook_email1'],
                $row['addressbook_roles_role']);
            $rowCount++;
            $totalRows++;
            if ($rowCount >= 24)
            {
                $pdf->ColoredTable($header, $data);
                if ($numrows - $totalRows > 0)
                {
                    $pdf->AddPage();
                }
                $rowCount = 0;
            }

        }
        if ($rowCount > 0)
        {
            $pdf->ColoredTable($header, $data);
        }

        // $pdf->ColoredTable($header, $data);
        // $pdf->AddPage();
        /*
        * $data = array(
        *             array(
        *                 'Mr',
        *                 'Keal, Barry',
        *                 '0151 526 4508',
        *                 '07792 616 411',
        *                 'barry@keal.me.uk',
        *                 '46 Eastway',
        *                 '',
        *                 'Maghull',
        *                 'Club Member'),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),

        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array(),
        *             array());
        *         $pdf->ColoredTable($header, $data);
        *         $pdf->AddPage();
        *         $pdf->ColoredTable($header, $data);
        *         $pdf->AddPage();
        */
        // $pdf->ColoredTable($header, $data);
        $pdf->Output('name.pdf', 'D');
        //$pdf->makePDF($text);
    }

    /**
     * addressbook_class::viewList()
     * 
     * @return
     */
    private function viewList()
    {
        $where = $this->createWhere();

        $this->template->from = $this->from;
        $this->template->search = $this->search;
        $this->template->rolesValue = $this->rolesValue;
        $text = '';
        $text .= $this->tp->parseTemplate($this->template->addressbookListHeader(), true);
        $qry = 'SELECT addressbook_id,
        addressbook_lastname,
        addressbook_firstname,
        addressbook_city,
        addressbook_phone,
        addressbook_mobile,
        addressbook_email1,addressbook_roles_role
        FROM #addressbook_entries LEFT JOIN #addressbook_roles ON addressbook_role=addressbook_roles_id
        ' . $where . ' 
        ORDER BY addressbook_lastname,addressbook_firstname
        LIMIT ' . $this->from . ',' . $this->prefs['perPage'];
        $numrows = $this->db->gen($qry, false);
        // var_dump($this->shortcodes);
        if ($numrows == 0)
        {
            $text .= $this->tp->parseTemplate($this->template->addressbookListNone(), true);
        } else
        {
            while ($row = $this->db->fetch())
            {
                $text .= $this->tp->parseTemplate($this->template->addressbookListRow($row), true, $this->shortcodes);
            }

        }
        $total = $this->db->count('addressbook_entries', '(addressbook_id)', $where, false);
        $amount = $this->prefs['perPage'];
        $current = $this->from;
        $url = rawurlencode(e_SELF . '?action=list&from=--FROM--&search=' . $this->search . '&roles=' . $this->rolesValue);
        $type = 'record';
        $parm = "total={$total}&amount={$amount}&current={$current}&type={$type}&url={$url}";

        $nextprev = $this->tp->parseTemplate("{NEXTPREV={$parm}}");
        $text .= $this->tp->parseTemplate($this->template->addressbookListFooter($nextprev), true);

        return $text;
    }
    /**
     * addressbook_class::ajaxPage()
     * 
     * @param integer $id
     * @return void
     */
    private function ajaxPage($id = 0)
    {
        if ($id == 0)
        {
            echo "invalid record";
        } else
        {
            $qry = 'SELECT *
        FROM #addressbook_entries 
        LEFT JOIN #addressbook_titles ON addressbook_title=addressbook_titles_id
        LEFT JOIN #addressbook_roles ON addressbook_role=addressbook_roles_id
        LEFT JOIN #addressbook_categories ON addressbook_category=addressbook_categories_id
        LEFT JOIN #addressbook_countries ON addressbook_country=addressbook_countries_id
        where addressbook_id=' . $id;
            $numrows = $this->db->gen($qry, false);
            if ($numrows !== 1)
            {
                echo "record not found";
            } else
            {
                $row = $this->db->fetch();
                $text .= $this->tp->parseTemplate($this->template->viewEntry($row), true);
                echo $text;
            }
        }
    }
    /**
     * addressbook_class::viewPage()
     * 
     * @param integer $id
     * @return
     */
    private function viewPage($id = 0)
    {
        if ($id == 0)
        {

            $text .= $this->tp->parseTemplate($this->template->noRecords(), true);
        } else
        {
            $qry = 'SELECT e.*,r.addressbook_roles_role
        FROM #addressbook_entries as e LEFT JOIN #addressbook_roles as r ON addressbook_role=addressbook_roles_id
        where addressbook_id=' . $id;
            $numrows = $this->db->gen($qry, false);
            if ($numrows !== 1)
            {
                echo "record not found";
            } else
            {
                $row = $this->db->fetch();
                $text .= $this->tp->parseTemplate($this->template->showEntry($row), true);
            }
        }
        return $text;
    }
}
/*
class ABPDF extends e107PDF
{
public function Header()
{
// Logo
$image_file = e_PLUGIN . 'addressbook/images/logo.png';
define('PDFLOGO', $image_file);
$this->Image($image_file, 10, 10, '', '', 'PNG', '', 'T', false, 300, '', false, false,
0, false, false, false);
$this->Image($image_file, 264, 10, '', '', 'PNG', '', 'T', false, 300, '', false, false,
0, false, false, false);
// Set font
$this->setXY(80, 15, false);
$this->SetTextColor(255, 207, 1);
$this->SetFillColor(0, 0, 255);
$this->SetFont('helvetica', 'B', 22);
///$this->StartTransform();

$this->RoundedRect($x = '80', $y = '8', $w = '120', $h = '100', $r = '3', $round_corner =
'1111', $style = 'CNZ', $border_style = array('all' => $ImageBorderArray));
//$this->StopTransform();
$this->Cell(120, 15, '  Maghull & District Lions Club ', 0, false, 'L', true, '',
0, false, 'M', 'M');


$this->SetFont('helvetica', 'B', 20);
// Title
$this->setXY(119, 29, false);
$this->SetTextColor(53, 0, 198);
$this->Cell(0, 15, ' Address Book ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
}

// Page footer
public function Footer()
{
// Position at 15 mm from bottom
$this->SetY(-15);
// Set font
$this->SetFont('helvetica', 'I', 9);
$this->Cell(0, 10, 'Confidential ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
// Page number
$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->
getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
}
function watermark($text = 'confidential', $positions = array(10, 10), $rotate =
array(
'45',
'50',
'180'))
{
$this->SetFont('helvetica', 'B', 50);
$this->SetTextColor(230, 230, 230);
$this->Rotate($rotate[0], $rotate[1], $rotate[2]);
$this->Text($positions[0], $positions[1], $text);
$this->Rotate(0);
$this->SetTextColor(0, 0, 0);
}
// Colored table
public function ColoredTable($header, $data)
{
// Colors, line width and bold font
$this->SetFillColor(255, 0, 0);
$this->SetTextColor(255);
$this->SetDrawColor(128, 0, 0);
$this->SetLineWidth(0.3);
$this->SetFont('', 'B');

// Header
$w = array(
9,
35,
32,
32,
40,
35,
30,
30,
35);
$font_size = $this->pixelsToUnits('26');

$this->SetFont('helvetica', '', $font_size, '', 'default', true);
$num_headers = count($header);
for ($i = 0; $i < $num_headers; ++$i) {
$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
}
$this->Ln();
// Color and font restoration
$this->SetFillColor(231, 242, 255);
$this->SetDrawColor(224, 224, 224);
$this->SetTextColor(0);
$this->SetFont('');
// Data
$fill = 0;
$font_size = $this->pixelsToUnits('22');

$this->SetFont('helvetica', '', $font_size, '', 'default', true);
foreach ($data as $row) {
$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
$this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill);
$this->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
$this->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill);
$this->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill);
$this->Cell($w[6], 6, $row[6], 'LR', 0, 'L', $fill);
$this->Cell($w[7], 6, $row[7], 'LR', 0, 'L', $fill);
$this->Cell($w[8], 6, $row[8], 'LR', 0, 'L', $fill);
$this->Ln();
$fill = !$fill;
}
$this->Cell(array_sum($w), 0, '', 'T');
}
}
*/
