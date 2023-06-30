<?php
# Script: add.melee.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.melee.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Melee Weapons</title>

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
function delete_melee( p_script_url ) {
  var question;
  var answer;

  question  = "The Melee Weapon may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Melee Weapon?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.melee.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_melee(p_script_url, update) {
  var am_form = document.dialog;
  var am_url;

  am_url  = '?update='   + update;
  am_url += "&id="       + am_form.id.value;

  am_url += "&melee_class="      + encode_URI(am_form.melee_class.value);
  am_url += "&melee_name="       + encode_URI(am_form.melee_name.value);
  am_url += "&melee_acc="        + encode_URI(am_form.melee_acc.value);
  am_url += "&melee_reach="      + encode_URI(am_form.melee_reach.value);
  am_url += "&melee_damage="     + encode_URI(am_form.melee_damage.value);
  am_url += "&melee_type="       + encode_URI(am_form.melee_type.value);
  am_url += "&melee_flag="       + encode_URI(am_form.melee_flag.value);
  am_url += "&melee_strength="   + am_form.melee_strength.checked;
  am_url += "&melee_ap="         + encode_URI(am_form.melee_ap.value);
  am_url += "&melee_avail="      + encode_URI(am_form.melee_avail.value);
  am_url += "&melee_perm="       + encode_URI(am_form.melee_perm.value);
  am_url += "&melee_basetime="   + encode_URI(am_form.melee_basetime.value);
  am_url += "&melee_duration="   + encode_URI(am_form.melee_duration.value);
  am_url += "&melee_index="      + encode_URI(am_form.melee_index.value);
  am_url += "&melee_cost="       + encode_URI(am_form.melee_cost.value);
  am_url += "&melee_book="       + encode_URI(am_form.melee_book.value);
  am_url += "&melee_page="       + encode_URI(am_form.melee_page.value);

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.melee.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickMelee' ).click(function() {
    $( "#dialogMelee" ).dialog('open');
  });

  $( "#dialogMelee" ).dialog({
    autoOpen: false,

    modal: true,
    height: 375,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogMelee" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_melee('add.melee.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Melee Weapon",
        click: function() {
          attach_melee('add.melee.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Melee Weapon",
        click: function() {
          attach_melee('add.melee.mysql.php', 0);
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

<form name="melee">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Melee Weapon Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('melee-help');">Help</a></th>
</tr>
</table>

<div id="melee-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Weapon Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickMelee" value="Add Melee Weapon"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Melee Weapons...')?></span>

</div>


<div id="dialogMelee" title="Melee Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="melee_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Melee\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="melee_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">ACC <input type="text" name="melee_acc" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Reach <input type="text" name="melee_reach" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Damage <input type="text" name="melee_damage" size="3"><input type="text" name="melee_type" size="3">(<input type="text" name="melee_flag" size="3">) Use Strength? <input type="checkbox" name="melee_strength"></td>
</tr>
<tr>
  <td class="ui-widget-content">AP <input type="text" name="melee_ap" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Avail <input type="text" name="melee_avail" size="3"><input type="text" name="melee_perm" size="3"> Base Time <input type="text" name="melee_basetime" size="6"> Duration <select name="melee_duration">
<option value="0">Unset</option>
<?php
  $q_string  = "select dur_id,dur_name ";
  $q_string .= "from duration ";
  $q_string .= "order by dur_id ";
  $q_duration = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_duration = mysql_fetch_array($q_duration)) {
    print "<option value=\"" . $a_duration['dur_id'] . "\">" . $a_duration['dur_name'] . "</option>\n";
  }
?>
</select> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Street Index <input type="text" name="melee_index" size="6"> (sr3)</td>
</tr>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="melee_cost" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="melee_book">
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
</select>: <input type="text" name="melee_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
