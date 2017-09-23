<?php


class addressbookTemplate
{
    private $roles;
    public $from;
    public $rolesValue;
    function __construct()
    {
        $sql = e107::getDb();
        $sql->select('addressbook_roles', 'addressbook_roles_id,addressbook_roles_role',
            '', 'nowhere');
        $this->roles[0] = 'All Roles';
        while ($row = $sql->fetch('assoc')) {

            $this->roles[$row['addressbook_roles_id']] = $row['addressbook_roles_role'];
        }
    }
    function notPermitted()
    {
        $retval .= '
<div id="addressbookHeading">
    <a href="../../index.php">
        <i class="fa fa-home fa-3" aria-hidden="true"></i>
    </a>
</div>
<div id="addressbookNoPermit">'.LAN_PLUGIN_ADDRESSBOOK_FRONT_NOTPERMITTED.'</div>';
        return $retval;
    }
    function noRecords()
    {
        $retval .= '
<div id="addressbookHeading">
    <a href="../../index.php">
        <i class="fa fa-home fa-3" aria-hidden="true"></i>
    </a>
</div>
<div id="addressbookNoRecords">".LAN_PLUGIN_ADDRESSBOOK_FRONT_NOMATCH."</div>';
        return $retval;
    }
    function addressbookListHeader()
    {
        $frm = e107::getForm();

        $select = $frm->search('search', $this->search, 'sname', 'roles', $this->roles,
            $this->rolesValue);
        $retval = '
        <div class="addressbookHead">';
        $retval .= $frm->open('addressbookSearch', 'get', e_SELF, null);
        $retval .= '
            <div class="addressbookSelect">' . $select . '</div>';
        $retval .= $frm->hidden('action', 'list');
        $retval .= $frm->hidden('from', $this->from);
        $retval .= $frm->close();
        $retval .= '
        </div>            
        <div id="addressbookPage">
        <table class="addressTable table table-bordered table-striped table-hover table-condensed table-responsive">
	<thead>
		<tr>
			<th>
				'.LAN_PLUGIN_ADDRESSBOOK_FRONT_NAME.'
			</th>
			<th>
				'.LAN_PLUGIN_ADDRESSBOOK_FRONT_TOWN.'
			</th>
			<th>
				'.LAN_PLUGIN_ADDRESSBOOK_FRONT_PHONE.'
			</th>
			<th>
				'.LAN_PLUGIN_ADDRESSBOOK_FRONT_MOBILE.'
			</th>
			<th>
				'.LAN_PLUGIN_ADDRESSBOOK_FRONT_EMAIL.'
			</th>
		</tr>
	</thead>
	<tbody>';
        return $retval;
    }
    function addressbookListRow($row)
    {
        $retval = '
		<tr id="listRow-' . $row['addressbook_id'] . '" class="addressBookRow editID' .
            $row['addressbook_id'] . '" >
			<td>
				' . $row['addressbook_lastname'] . ', ' . $row['addressbook_firstname'] . '
			</td>
			<td>
				' . $row['addressbook_city'] . '
			</td>
			<td>
				' . $row['addressbook_phone'] . '
			</td>
			<td>
			     ' . $row['addressbook_mobile'] . '
			</td>
			<td>
			     ' . $row['addressbook_email1'] . '
			</td>
		</tr>';
        return $retval;
    }
    function addressbookListNone()
    {
        $retval = '
		<tr>
			<td colspan="5">
				<div id="addressbookNone" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_NOMATCH.'</div>
			</td>

		</tr>';
        return $retval;
    }

    function addressbookListFooter($nextPrev = '')
    {
        $retval = '  
	</tbody>
</table>
</div>
<div style="font-size:32px;float:left;display:inline;">' . $nextPrev . '</div>
<div style="font-size:32px;float:right;display:inline;">
    <a href="' . e_PLUGIN_ABS .
            'addressbook/index.php?action=pdf" id="addressbookpdf" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
    <a href="' . e_PLUGIN_ABS .
            'addressbook/index.php?action=csv&search='.$this->search.'&role='.$this->rolesValue.'"><i class="fa fa-download" aria-hidden="true"></i></a>
    <!--<a href="' . e_PLUGIN_ABS .
            'addressbook/index.php?action=prn"><i class="fa fa-print" aria-hidden="true"></i></a>-->
</div>
<a href="' . e_PLUGIN_ABS .
            'addressbook/index.php?action=ajaxview&id=" id="modallink" data-remote="false" data-toggle="modal" data-target="#myModal" class="btnx btnx-default"></a>

<!-- Default bootstrap modal example -->

<div class="modal fade" id="myAddrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">'.LAN_PLUGIN_ADDRESSBOOK_FRONT_ENTRY.'</h4>
      </div>
      <div id="modal-body" class="modal-body">
            <div id="ajaxSpinner" class="lds-css ng-scope">
                <div id="ajaxSpinnerSpin">
                    <div  class="lds-pacman">
                        <div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <div id="loadingAB">'.LAN_PLUGIN_ADDRESSBOOK_FRONT_LOADING.'</div>
                </div>
                
            </div> 
            
            <div id="modalContent"></div> 
      </div> <!-- end of modal body -->
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">'.LAN_PLUGIN_ADDRESSBOOK_FRONT_CLOSE.'</button>
        </div>
    </div>
  </div>
</div>';

        return $retval;
    }
    function viewEntry($row)
    {
        $retval = '
<div id="addressbookPage">
        <style type="text/css"></style>
        <table  >
            <tbody>
                <tr  >
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_FULLNAME.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_lastname'] .
            ', ' . $row['addressbook_firstname'] . '</td>
                    <td class="titleCol commsCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_PHONE.'</td>
                    <td class="contentCol commsCell" >' . $row['addressbook_phone'] .
            '</td>
                </tr>
                <tr  >
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_ADDRESS.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_addr1'] .
            '</td>
                    <td class="titleCol commsCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_MOBILE.'</td>
                    <td class="contentCol commsCell" >' . $row['addressbook_mobile'] .
            '</td>
                </tr>
                <tr  >
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_ADDRESS.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_addr2'] .
            '</td>
                    <td class="titleCol commsCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_EMAIL.'</td>
                    <td class="contentCol commsCell" >' . $row['addressbook_email1'] .
            '</td>
                </tr>
                <tr  >
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_TOWN.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_city'] .
            '</td>
                    <td class="titleCol commsCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_EMAILALT.'</td>
                    <td class="contentCol commsCell" >' . $row['addressbook_email2'] .
            '</td>
                </tr>
                <tr  >
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_COUNTY.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_county'] .
            '</td>
                    <td class="titleCol commsCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_WEB.'</td>
                    <td class="contentCol commsCell" >' . $row['addressbook_website'] .
            '</td>
                </tr>
                <tr style="height: 20px;">
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_POST.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_postcode'] .
            '</td>
                    <td class="titleCol addressCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_COUNTRY.'</td>
                    <td class="contentCol addressCell" >' . $row['addressbook_countries_name'] .
            '</td>
                </tr>
                <tr style="height: 20px;">
                    <td class="titleCol roleCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_ROLE.'</td>
                    <td class="contentCol roleCell" >' . $row['addressbook_roles_role'] .
            '</td>
                    <td class="titleCol roleCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_CATEGORY.'</td>
                    <td class="contentCol roleCell" >' . $row['addressbook_categories_name'] .
            '</td>
                </tr>        
                <tr style="height: 20px;">
                    <td class="titleCol notesCell" >'.LAN_PLUGIN_ADDRESSBOOK_FRONT_NOTES.'</td>
			         <td  class="contentCol notesCell"  colspan="3">' . $row['addressbook_comments'] .
            '</td>
                </tr>
        	</tbody>
        </table>
    </div>
<!-- DivTable.com -->
';
        return $retval;
    }
    function showEntry($row)
    {
        $retval = '
<div id="addressbookHeading">
    <a href="index.php?action=list&from=' . $this->from . '&search=' . $this->
            search . '&role=' . $this->rolesValue . '">
        <i class="fa fa-home fa-3" aria-hidden="true"></i>
    </a>
</div>';
        $retval .= $this->viewEntry($row);
        /*
        <div id="addressbookPage">
        <table  >
        <tbody>
        <tr  >
        <td class="titleCol  addressCell" >Name</td>
        <td class="contentCol addressCell" >'.$row['addressbook_lastname'].', '.$row['addressbook_firstname'].'</td>
        <td class="titleCol commsCell" >Phone</td>
        <td class="contentCol commsCell" >'.$row['addressbook_phone'].'</td>
        </tr>
        <tr  >
        <td class="titleCol addressCell" >Address</td>
        <td class="contentCol addressCell" >'.$row['addressbook_addr1'].'</td>
        <td class="titleCol commsCell" >Mobile</td>
        <td class="contentCol commsCell" >'.$row['addressbook_mobile'].'</td>
        </tr>
        <tr  >
        <td class="titleCol addressCell" >Address</td>
        <td class="contentCol addressCell" >'.$row['addressbook_addr2'].'</td>
        <td class="titleCol commsCell" >Email</td>
        <td class="contentCol commsCell" >'.$row['addressbook_email1'].'</td>
        </tr>
        <tr  >
        <td class="titleCol addressCell" >Town</td>
        <td class="contentCol addressCell" >'.$row['addressbook_city'].'</td>
        <td class="titleCol commsCell" >Email (alt)</td>
        <td class="contentCol commsCell" >'.$row['addressbook_email2'].'</td>
        </tr>
        <tr  >
        <td class="titleCol addressCell" >County</td>
        <td class="contentCol addressCell" >'.$row['addressbook_county'].'</td>
        <td class="titleCol commsCell" >Web</td>
        <td class="contentCol commsCell" >'.$row['addressbook_website'].'</td>
        </tr>
        <tr style="height: 20px;">
        <td class="titleCol addressCell" >Postcode</td>
        <td class="contentCol addressCell" >'.$row['addressbook_postcode'].'</td>
        <td class="titleCol commsCell" >&nbsp;</td>
        <td class="contentCol commsCell" >&nbsp;</td>
        </tr>
        <tr style="height: 20px;">
        <td class="titleCol roleCell" >Role</td>
        <td class="contentCol roleCell" >'.$row['addressbook_roles_role'].'</td>
        <td class="titleCol roleCell" >Category</td>
        <td class="contentCol roleCell" >'.$row['addressbook_categories_name'].'</td>
        </tr>        
        <tr style="height: 20px;">
        <td class="titleCol notesCell" >Notes</td>
        <td  class="contentCol notesCell"  colspan="3">'.$row['addressbook_comments'].'</td>
        </tr>

        </tbody>
        </table>
        </div>
        <!-- DivTable.com -->
        ';
        */
        return $retval;
    }
}
