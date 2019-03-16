<?php
# Script: add.ammo.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.ammo.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Ammunition</title>

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
function delete_ammo( p_script_url ) {
  var question;
  var answer;

  question  = "The Ammunition may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Ammunition?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.ammo.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_ammo(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&ammo_class="   + encode_URI(aa_form.ammo_class.value);
  aa_url += "&ammo_name="    + encode_URI(aa_form.ammo_name.value);
  aa_url += "&ammo_rounds="  + encode_URI(aa_form.ammo_rounds.value);
  aa_url += "&ammo_rating="  + encode_URI(aa_form.ammo_rating.value);
  aa_url += "&ammo_mod="     + encode_URI(aa_form.ammo_mod.value);
  aa_url += "&ammo_ap="      + encode_URI(aa_form.ammo_ap.value);
  aa_url += "&ammo_blast="   + encode_URI(aa_form.ammo_blast.value);
  aa_url += "&ammo_armor="   + encode_URI(aa_form.ammo_armor.value);
  aa_url += "&ammo_avail="   + encode_URI(aa_form.ammo_avail.value);
  aa_url += "&ammo_perm="    + encode_URI(aa_form.ammo_perm.value);
  aa_url += "&ammo_cost="    + encode_URI(aa_form.ammo_cost.value);
  aa_url += "&ammo_book="    + encode_URI(aa_form.ammo_book.value);
  aa_url += "&ammo_page="    + encode_URI(aa_form.ammo_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.ammo.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.ammo.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAmmunition' ).click(function() {
    $( "#dialogAmmunition" ).dialog('open');
  });

  $( "#dialogAmmunition" ).dialog({
    autoOpen: false,

    modal: true,
    height: 200,
    width:  820,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogAmmunition" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_ammo('add.ammo.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Ammunition",
        click: function() {
          attach_ammo('add.ammo.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Ammunition",
        click: function() {
          attach_ammo('add.ammo.mysql.php', 0);
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

<form name="ammo">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Ammunition Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ammo-help');">Help</a></th>
</tr>
</table>

<div id="ammo-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Ammunition Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addammo" id="clickAmmunition" value="Add Ammunition"></td>
</tr>
</table>

<span id="mysql_table"><?php print wait_Process('Loading Ammunition...')?></span>

</form>

</div>


<div id="dialogAmmunition" title="Ammunition">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="6">Ammunition Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Class <select name="ammo_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on class.class_subjectid = subjects.sub_id ";
  $q_string .= "where sub_name = \"Ammunition\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Name <input type="text" name="ammo_name" size="20"></td>
  <td class="ui-widget-content">Rounds <input type="text" name="ammo_rounds" size="4"></td>
  <td class="ui-widget-content">Rating <input type="text" name="ammo_rating" size="4"></td>
  <td class="ui-widget-content" colspan="2">Damage Modifier <input type="text" name="ammo_mod" size="15"></td>
</tr>
<tr>
  <td class="ui-widget-content">AP Modifier <input type="text" name="ammo_ap" size="5"></td>
  <td class="ui-widget-content">Blast <input type="text" name="ammo_blast" size="10"></td>
  <td class="ui-widget-content">Armor Used <input type="text" name="ammo_armor" size="3"></td>
  <td class="ui-widget-content">Avail <input type="text" name="ammo_avail" size="3"><input type="text" name="ammo_perm" size="3"></td>
  <td class="ui-widget-content">Cost <input type="text" name="ammo_cost" size="10"></td>
  <td class="ui-widget-content">Book  <select name="ammo_book">
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
</select>: <input type="text" name="ammo_page" size="3"></td>
</tr>
</table>


</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
