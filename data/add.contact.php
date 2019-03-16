<?php
# Script: add.contact.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.contact.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Contacts</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel(1)) {
?>
function delete_contact( p_script_url ) {
  var question;
  var answer;

  question  = "The Contact may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Contact?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.contact.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_contact(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&con_name="       + encode_URI(ac_form.con_name.value);
  ac_url += "&con_archetype="  + encode_URI(ac_form.con_archetype.value);
  ac_url += "&con_character="  + ac_form.con_character.value;
  ac_url += "&con_book="       + encode_URI(ac_form.con_book.value);
  ac_url += "&con_page="       + encode_URI(ac_form.con_page.value);
  ac_url += "&con_owner="      + ac_form.con_owner.value;

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.contact.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickContact' ).click(function() {
    $( "#dialogContact" ).dialog('open');
  });

  $( "#dialogContact" ).dialog({
    autoOpen: false,
    modal: true,
    height: 180,
    width:  690,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogContact" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_contact('add.contact.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Contact",
        click: function() {
          attach_contact('add.contact.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Contact",
        click: function() {
          attach_contact('add.contact.mysql.php', 0);
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

<form name="contact">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Contact Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('contact-help');">Help</a></th>
</tr>
</table>

<div id="contact-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Commlink Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickContact" value="Add New Contact"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Contacts...')?></span>

</div>


<div id="dialogContact" title="Contact">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="8">Contact Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="con_name" size="30"></td>
  <td class="ui-widget-content" colspan="3">Archetype <input type="text" name="con_archetype" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Character <select name="con_character">
<option value="0">None</option>
<?php
  $q_string  = "select runr_id,runr_archetype,runr_name ";
  $q_string .= "from runners ";
  $q_string .= "order by runr_archetype,runr_name ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_runners = mysql_fetch_array($q_runners)) {
    print "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_archetype'] . " (" . $a_runners['runr_name'] . ")</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Book  <select name="con_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Page <input type="text" name="con_page" size="10"></td>
  <td class="ui-widget-content">Owner <select name="con_owner">
<option value="0">None</option>
<?php
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from users ";
  $q_string .= "order by usr_last,usr_first ";
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_users = mysql_fetch_array($q_users)) {
    print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
