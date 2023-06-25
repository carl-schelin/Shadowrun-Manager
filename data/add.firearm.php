<?php
# Script: add.firearm.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.firearm.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Weapons</title>

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
function delete_firearm( p_script_url ) {
  var question;
  var answer;

  question  = "The Firearm may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Firearm?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.firearm.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_firearm(p_script_url, update) {
  var af_form = document.dialog;
  var af_url;

  af_url  = '?update='   + update;
  af_url += "&id="       + af_form.id.value;

  af_url += "&fa_class="   + encode_URI(af_form.fa_class.value);
  af_url += "&fa_name="    + encode_URI(af_form.fa_name.value);
  af_url += "&fa_acc="     + encode_URI(af_form.fa_acc.value);
  af_url += "&fa_damage="  + encode_URI(af_form.fa_damage.value);
  af_url += "&fa_type="    + encode_URI(af_form.fa_type.value);
  af_url += "&fa_flag="    + encode_URI(af_form.fa_flag.value);
  af_url += "&fa_ap="      + encode_URI(af_form.fa_ap.value);
  af_url += "&fa_mode1="   + encode_URI(af_form.fa_mode1.value);
  af_url += "&fa_mode2="   + encode_URI(af_form.fa_mode2.value);
  af_url += "&fa_mode3="   + encode_URI(af_form.fa_mode3.value);
  af_url += "&fa_ar1="     + encode_URI(af_form.fa_ar1.value);
  af_url += "&fa_ar2="     + encode_URI(af_form.fa_ar2.value);
  af_url += "&fa_ar3="     + encode_URI(af_form.fa_ar3.value);
  af_url += "&fa_ar4="     + encode_URI(af_form.fa_ar4.value);
  af_url += "&fa_ar5="     + encode_URI(af_form.fa_ar5.value);
  af_url += "&fa_rc="      + encode_URI(af_form.fa_rc.value);
  af_url += "&fa_fullrc="  + encode_URI(af_form.fa_fullrc.value);
  af_url += "&fa_ammo1="   + encode_URI(af_form.fa_ammo1.value);
  af_url += "&fa_clip1="   + encode_URI(af_form.fa_clip1.value);
  af_url += "&fa_ammo2="   + encode_URI(af_form.fa_ammo2.value);
  af_url += "&fa_clip2="   + encode_URI(af_form.fa_clip2.value);
  af_url += "&fa_avail="   + encode_URI(af_form.fa_avail.value);
  af_url += "&fa_perm="    + encode_URI(af_form.fa_perm.value);
  af_url += "&fa_cost="    + encode_URI(af_form.fa_cost.value);
  af_url += "&fa_book="    + encode_URI(af_form.fa_book.value);
  af_url += "&fa_page="    + encode_URI(af_form.fa_page.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.firearm.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickFirearm' ).click(function() {
    $( "#dialogFirearm" ).dialog('open');
  });

  $( "#dialogFirearm" ).dialog({
    autoOpen: false,

    modal: true,
    height: 425,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogFirearm" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_firearm('add.firearm.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Firearm",
        click: function() {
          attach_firearm('add.firearm.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Firearm",
        click: function() {
          attach_firearm('add.firearm.mysql.php', 0);
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

<form name="firearm">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Firearm Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('firearm-help');">Help</a></th>
</tr>
</table>

<div id="firearm-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickFirearm" value="Add New Firearm"></td>
</tr>
</table>

</form>


<span id="mysql_table"><?php print wait_Process('Loading Firearms...')?></span>


</div>



<div id="dialogFirearm" title="Firearms">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="fa_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Firearms\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Name <input type="text" name="fa_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Accuracy <input type="text" name="fa_acc" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Damage Value <input type="text" name="fa_damage" size="2"><input type="text" name="fa_type" size="2">(<input type="text" name="fa_flag" size="2">)</td>
</tr>
<tr>
  <td class="ui-widget-content">AP <input type="text" name="fa_ap" size="3"> (sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Mode <input type="text" name="fa_mode1" size="3">/<input type="text" name="fa_mode2" size="3">/<input type="text" name="fa_mode3" size="3"></td>
<tr>
</tr>
  <td class="ui-widget-content">Attack Rating <input type="text" name="fa_ar1" size="3">/<input type="text" name="fa_ar2" size="3">/<input type="text" name="fa_ar3" size="3">/<input type="text" name="fa_ar4" size="3">/<input type="text" name="fa_ar5" size="3"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">RC <input type="text" name="fa_rc" size="3">(<input type="text" name="fa_fullrc" size="3">) (sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Ammo <input type="text" name="fa_ammo1" size="3">(<input type="text" name="fa_clip1" size="3">)/<input type="text" name="fa_ammo2" size="3">(<input type="text" name="fa_clip2" size="3">)</td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="fa_avail" size="3"><input type="text" name="fa_perm" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="fa_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="fa_book">
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
</select>: <input type="text" name="fa_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
