<?php
# Script: add.command.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.command.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

# build a command manafacturer's ID
  $cmd_access =
    dechex(rand(0,32767)) . ":" .
    dechex(rand(0,32767)) . ":" .
    dechex(rand(0,32767));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Rigger Command Consoles</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_command( p_script_url ) {
  var question;
  var answer;

  question  = "The Rigger Command Console may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Rigger Command Console?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.command.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_command(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&cmd_brand="      + encode_URI(ac_form.cmd_brand.value);
  ac_url += "&cmd_model="      + encode_URI(ac_form.cmd_model.value);
  ac_url += "&cmd_rating="     + encode_URI(ac_form.cmd_rating.value);
  ac_url += "&cmd_data="       + encode_URI(ac_form.cmd_data.value);
  ac_url += "&cmd_firewall="   + encode_URI(ac_form.cmd_firewall.value);
  ac_url += "&cmd_programs="   + encode_URI(ac_form.cmd_programs.value);
  ac_url += "&cmd_access="     + encode_URI(ac_form.cmd_access.value);
  ac_url += "&cmd_avail="      + encode_URI(ac_form.cmd_avail.value);
  ac_url += "&cmd_perm="       + encode_URI(ac_form.cmd_perm.value);
  ac_url += "&cmd_cost="       + encode_URI(ac_form.cmd_cost.value);
  ac_url += "&cmd_book="       + encode_URI(ac_form.cmd_book.value);
  ac_url += "&cmd_page="       + encode_URI(ac_form.cmd_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.command.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.command.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickCommand' ).click(function() {
    $( "#dialogCommand" ).dialog('open');
  });

  $( "#dialogCommand" ).dialog({
    autoOpen: false,

    modal: true,
    height: 375,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogCommand" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_command('add.command.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Command Console",
        click: function() {
          attach_command('add.command.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Command Console",
        click: function() {
          attach_command('add.command.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="command">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Rigger Command Console Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('command-help');">Help</a></th>
</tr>
</table>

<div id="command-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Rigger Command Console Form</strong>
  <ul>
    <li><strong>Name</strong> - The name of the Metatype.</li>
    <li><strong>Walk</strong> - The Metatype walking speed.</li>
    <li><strong>Run</strong> - The Metatype run speed.</li>
    <li><strong>Swim</strong> - The Metatype swim speed.</li>
    <li><strong>Book</strong> - Select the book where this table is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addlink" id="clickCommand" value="Add Command Console"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Rigger Command Consoles...')?></span>

</div>


<div id="dialogCommand" title="Command Console Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.command.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
