<?php
$plugname=basename(__DIR__);
$name="e107:plugins:".$plugname;
$button = "
    <a href='http://manual.keal.me.uk/doku.php?id={$name}' id='HelpButton' target='_blank'>                    
        <button type='button' class='btn btn-info' style='font-size:14px;color:white;'>
            <i class='fa fa-info' aria-hidden='true'></i> ".LAN_HELP_TITLE."
        </button>
    </a>";
$buttonBugs = "
    <a href='https://github.com/G4HDU-plugins/{$plugname}/issues' id='HelpIssues' target='_blank'>                    
        <button type='button' class='btn btn-info' style='font-size:14px;color:white;'>
       <i class='fa fa-bug' aria-hidden='true'></i> ".LAN_HELP_BUG."
        </button>
    </a>";
$helplink_text = "<div style='width=100%;margin:0 auto;text-align: center;' >". LAN_HELP_LINK . "<br>
" . $button ."<br><br>". LAN_HELP_BUGS ."<br>" .$buttonBugs."</div>";
$ns->tablerender(LAN_HELP_TITLE, $helplink_text, 'hduhelp');

?>
