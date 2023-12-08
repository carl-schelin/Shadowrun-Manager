<?php
# Script: add.groups.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('2');

  $package = "add.groups.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Groups</title>

<style type='text/css' title='currentStyle' media='screen'>
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
function delete_line( p_script_url ) {
  var question;
  var answer;

  question  = "The preference is to change the group status from Enabled to Disabled\n";
  question += "which prevents the orphaning of group owned or identified information. Deleting\n";
  question += "the group should be done when removing duplicate records or if you know the \n";
  question += "group has no group managed information.\n\n";

  question += "Delete this Group anyway?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}
<?php
  }
?>

function attach_file( p_script_url, update ) {
  var af_form = document.groups;
  var af_url;

  af_url  = '?update='   + update;
  af_url += '&id='       + af_form.id.value;

  af_url += "&grp_name="      + encode_URI(af_form.grp_name.value);
  af_url += "&grp_email="     + encode_URI(af_form.grp_email.value);
  af_url += "&grp_disabled="  + af_form.grp_disabled.value;
  af_url += "&grp_owner="     + af_form.grp_owner.value;

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.groups.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="groups">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('group-hide');">Group Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('group-help');">Help</a></th>
</tr>
</table>

<div id="group-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Group Form</strong>
  <ul>
    <li><strong>Group Name</strong> - The name as presented in any group selection drop down.</li>
    <li><strong>E-Mail</strong> - The e-mail address for this group. This is used by RSDP for example to send tasks to the group.</li>
    <li><strong>Status</strong> - Change the status of the group here. Disabled groups will not be shown in the group selection menus.</li>
  </ul></li>
</ul>

</div>

</div>


<div id="group-hide" style="display: none">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content button">
<input type="button" disabled="true" name="update" value="Update Group" onClick="javascript:attach_file('add.groups.mysql.php', 1);hideDiv('group-hide');">
<input type="hidden" name="id" value="0">
<input type="button"                 name="addnew" value="Add Group"    onClick="javascript:attach_file('add.groups.mysql.php', 0);">
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="4">Group Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Group Name: <input type="text" name="grp_name" size="40"></td>
  <td class="ui-widget-content">E-Mail: <input type="text" name="grp_email" size="40"></td>
  <td class="ui-widget-content">Owner <select name="grp_owner">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from users ";
  $q_string .= "where usr_disabled = 0 and usr_id > 1 ";
  $q_string .= "order by usr_last,usr_first ";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_users = mysqli_fetch_array($q_users)) {
    print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Status <select name="grp_disabled">
<option value="0">Enabled</option>
<option value="1">Disabled</option>
</select></td>
</tr>
</table>

</div>

<span id="table_mysql"></span>

</div>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
