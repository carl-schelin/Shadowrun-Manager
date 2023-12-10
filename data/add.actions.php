<?php
# Script: add.actions.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.actions.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Matrix Actions</title>

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
function delete_action( p_script_url ) {
  var question;
  var answer;

  question = "Delete this Matrix Action?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.actions.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_action(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&action_name="       + encode_URI(aa_form.action_name.value);
  aa_url += "&action_type="       + radio_Loop(aa_form.action_type, 2);
  aa_url += "&action_level="      + radio_Loop(aa_form.action_level, 2);
  aa_url += "&action_attack="     + encode_URI(aa_form.action_attack.value);
  aa_url += "&action_defense="    + encode_URI(aa_form.action_defense.value);
  aa_url += "&action_outsider="   + aa_form.action_outsider.checked;
  aa_url += "&action_user="       + aa_form.action_user.checked;
  aa_url += "&action_admin="      + aa_form.action_admin.checked;
  aa_url += "&action_book="       + encode_URI(aa_form.action_book.value);
  aa_url += "&action_page="       + encode_URI(aa_form.action_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.actions.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickActions' ).click(function() {
    $( "#dialogActions" ).dialog('open');
  });

  $( "#dialogActions" ).dialog({
    autoOpen: false,

    modal: true,
    height: 300,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogActions" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_action('add.actions.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Actions",
        click: function() {
          attach_action('add.actions.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Actions",
        click: function() {
          attach_action('add.actions.mysql.php', 0);
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

<form name="actions">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Matrix Actions Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('actions-help');">Help</a></th>
</tr>
</table>

<div id="actions-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>actions Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickActions" value="Add Matrix Action"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Sprites...')?></span>

</div>


<div id="dialogActions" title="Matrix Action Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.actions.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
