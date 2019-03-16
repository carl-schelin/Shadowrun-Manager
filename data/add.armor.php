<?php
# Script: add.armor.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.armor.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Clothing And Armor</title>

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
function delete_armor( p_script_url ) {
  var question;
  var answer;

  question  = "The Armor may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Armor?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.armor.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_armor(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&arm_class="     + encode_URI(aa_form.arm_class.value);
  aa_url += "&arm_name="      + encode_URI(aa_form.arm_name.value);
  aa_url += "&arm_rating="    + encode_URI(aa_form.arm_rating.value);
  aa_url += "&arm_capacity="  + encode_URI(aa_form.arm_capacity.value);
  aa_url += "&arm_avail="     + encode_URI(aa_form.arm_avail.value);
  aa_url += "&arm_perm="      + encode_URI(aa_form.arm_perm.value);
  aa_url += "&arm_cost="      + encode_URI(aa_form.arm_cost.value);
  aa_url += "&arm_book="      + encode_URI(aa_form.arm_book.value);
  aa_url += "&arm_page="      + encode_URI(aa_form.arm_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.armor.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.armor.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickArmor' ).click(function() {
    $( "#dialogArmor" ).dialog('open');
  });

  $( "#dialogArmor" ).dialog({
    autoOpen: false,

    modal: true,
    height: 200,
    width:  800,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogArmor" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_armor('add.armor.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Armor",
        click: function() {
          attach_armor('add.armor.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Armor",
        click: function() {
          attach_armor('add.armor.mysql.php', 0);
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

<form name="armor">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Armor And Clothing Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('armor-help');">Help</a></th>
</tr>
</table>

<div id="armor-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addarm" id="clickArmor" value="Add Armor"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Armor...')?></span>

</div>


<div id="dialogArmor" title="Armor">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="5">Armor Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Class <select name="arm_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Clothing And Armor\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="2">Name <input type="text" id="arm_name" name="arm_name" size="40"></td>
  <td class="ui-widget-content">Rating <input type="text" name="arm_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Capacity <input type="text" name="arm_capacity" size="3"></td>
  <td class="ui-widget-content">Cost <input type="text" name="arm_cost" size="10"></td>
  <td class="ui-widget-content">Avail <input type="text" name="arm_avail" size="3"><input type="text" name="arm_perm" size="3"></td>
  <td class="ui-widget-content">Book  <select name="arm_book">
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
</select>: <input type="text" name="arm_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
