<?php
# Script: add.members.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('2');

  $package = "add.members.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  } else {
    $formVars['id'] = 0;
  }

  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from groups ";
  $q_string .= "where grp_id = " . $formVars['id'] . " ";
  $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_groups = mysql_fetch_array($q_groups);
  if (mysql_num_rows($q_groups) > 0) {
    $formVars['grp_name'] = $a_groups['grp_name'];
  } else {
    $formVars['grp_name'] = "Unknown";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Group Members</title>

<style type='text/css' title='currentStyle' media='screen'>
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php
  if (check_userlevel(2)) {
?>
function delete_line( p_script_url ) {
  var question;
  var answer;

  question = "Delete this Group Member?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.members.mysql.php?update=-1&mem_group=<?php print $formVars['id']; ?>');
  }
}
<?php
  }
?>

function attach_file( p_script_url, update ) {
  var af_form = document.members;
  var af_url;
  var question;
  var answer;

  af_url  = '?update='   + update;
  af_url += '&id='       + af_form.id.value;

  af_url += "&mem_runner="    + encode_URI(af_form.mem_runner.value);
  af_url += "&mem_group="     + <?php print $formVars['id']; ?>;

  question = "This will send an email to the Runner's owner.";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url + af_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}

function clear_fields() {
  show_file('add.members.mysql.php?update=-1&mem_group=<?php print $formVars['id']; ?>');
}

$(document).ready( function() {
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="members">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('members-hide');">Group Member Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('members-help');">Help</a></th>
</tr>
</table>

<div id="members-help" style="display: none">

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


<div id="members-hide" style="display: none">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content button">
<input type="button" disabled="true" name="update" value="Update Group Member" onClick="javascript:attach_file('add.members.mysql.php', 1);hideDiv('members-hide');">
<input type="hidden" name="id" value="0">
<input type="button"                 name="addnew" value="Add Group Member"    onClick="javascript:attach_file('add.members.mysql.php', 0);">
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="4">Group Member Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Group Name: <?php print $formVars['grp_name']; ?></td>
  <td class="ui-widget-content">Available Runner: <select name="mem_runner">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select runr_id,runr_name,runr_archetype,meta_name ";
  $q_string .= "from runners ";
  $q_string .= "left join versions on versions.ver_id = runners.runr_version ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "where runr_available = 1 and ver_active = 1 ";
  $q_string .= "order by runr_name ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_runners = mysql_fetch_array($q_runners)) {
    print "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_name'] . " (" . $a_runners['meta_name'] . " " . $a_runners['runr_archetype'] . ")</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Status: <select disabled="true" name="mem_invite">
<option value="0">Declined</option>
<option value="1">Accepted</option>
<option value="2">Pending</option>
</select></td>
  <td class="ui-widget-content">Active? <input type="text" name="mem_active" size="20"></td>
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
