<?php
# Script: add.accessory.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.accessory.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Accessories</title>

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
function delete_accessory( p_script_url ) {
  var question;
  var answer;

  question  = "The Accessory will also be cleared from any character that owns one.\n";
  question += "Delete this Accessory?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.accessory.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_accessory(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&acc_type="      + encode_URI(aa_form.acc_type.value);
  aa_url += "&acc_class="     + encode_URI(aa_form.acc_class.value);
  aa_url += "&acc_accessory=" + encode_URI(aa_form.acc_accessory.value);
  aa_url += "&acc_name="      + encode_URI(aa_form.acc_name.value);
  aa_url += "&acc_mount="     + encode_URI(aa_form.acc_mount.value);
  aa_url += "&acc_essence="   + encode_URI(aa_form.acc_essence.value);
  aa_url += "&acc_rating="    + encode_URI(aa_form.acc_rating.value);
  aa_url += "&acc_capacity="  + encode_URI(aa_form.acc_capacity.value);
  aa_url += "&acc_avail="     + encode_URI(aa_form.acc_avail.value);
  aa_url += "&acc_perm="      + encode_URI(aa_form.acc_perm.value);
  aa_url += "&acc_basetime="  + encode_URI(aa_form.acc_basetime.value);
  aa_url += "&acc_duration="  + encode_URI(aa_form.acc_duration.value);
  aa_url += "&acc_index="     + encode_URI(aa_form.acc_index.value);
  aa_url += "&acc_cost="      + encode_URI(aa_form.acc_cost.value);
  aa_url += "&acc_book="      + encode_URI(aa_form.acc_book.value);
  aa_url += "&acc_page="      + encode_URI(aa_form.acc_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.accessory.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAccessory' ).click(function() {
    $( "#dialogAccessory" ).dialog('open');
  });

  $( "#dialogAccessory" ).dialog({
    autoOpen: false,
    modal: true,
    height: 425,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogAccessory" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_accessory('add.accessory.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Accessory",
        click: function() {
          attach_accessory('add.accessory.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Accessory",
        click: function() {
          attach_accessory('add.accessory.mysql.php', 0);
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

<form name="accessory">

<div id="main">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Accessory Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('accessory-help');">Help</a></th>
</tr>
</table>

<div id="accessory-help" style="<?php print $display; ?>">

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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickAccessory" value="Add New Accessory"</td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Accessories...')?></span>

</div>



<div id="dialogAccessory" title="Accessory Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Type <select name="acc_type">
<?php
  $q_string  = "select sub_id,sub_name ";
  $q_string .= "from subjects ";
  $q_string .= "order by sub_name ";
  $q_subjects = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_subjects = mysql_fetch_array($q_subjects)) {
    print "<option value=\"" . $a_subjects['sub_id'] . "\">" . $a_subjects['sub_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Class <select name="acc_class">
<option value="0">Any Subheading</option>
<?php
  $q_string  = "select class_id,class_name,sub_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "order by sub_name,class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">(" . $a_class['sub_name'] . ") " . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Accessory To <input type="text" name="acc_accessory" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Accessory Name <input type="text" name="acc_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Mount <input type="text" name="acc_mount" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence <input type="text" name="acc_essence" size="4"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="acc_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Capacity <input type="text" name="acc_capacity" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Avail <input type="text" name="acc_avail" size="3"><input type="text" name="acc_perm" size="3"> Base Time <input type="text" name="acc_basetime" size="6"> Duration <select name="acc_duration">
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
  <td class="ui-widget-content">Street Index <input type="text" name="acc_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="acc_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="acc_book">
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
</select>: <input type="text" name="acc_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
