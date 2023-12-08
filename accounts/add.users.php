<?php
# Script: add.users.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.users.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Users</title>

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
function delete_user( p_script_url ) {
  var question;
  var answer;

  question  = "The preference is to change the user access level from Enabled to Disabled\n";
  question += "which prevents the orphaning of user owned or identified information. Deleting\n";
  question += "the user should be done when removing duplicate records or if you know the \n";
  question += "user has no user managed information.\n\n";

  question += "Delete this User anyway?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.users.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_users(p_script_url, update) {
  var au_form = document.user;
  var au_url;

  au_url  = '?update='   + update;
  au_url += "&id="       + au_form.id.value;

  au_url += "&usr_first="      + encode_URI(au_form.usr_first.value);
  au_url += "&usr_last="       + encode_URI(au_form.usr_last.value);
  au_url += "&usr_name="       + encode_URI(au_form.usr_name.value);
  au_url += "&usr_disabled="   + au_form.usr_disabled.value;
  au_url += "&usr_level="      + au_form.usr_level.value;
  au_url += "&usr_email="      + encode_URI(au_form.usr_email.value);
  au_url += "&usr_phone="      + encode_URI(au_form.usr_phone.value);
  au_url += "&usr_theme="      + au_form.usr_theme.value;
  au_url += "&usr_passwd="     + encode_URI(au_form.usr_passwd.value);
  au_url += "&usr_reenter="    + encode_URI(au_form.usr_reenter.value);
  au_url += "&usr_reset="      + au_form.usr_reset.checked;

  script = document.createElement('script');
  script.src = p_script_url + au_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.users.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<form name="user">

<div id="main">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('user-hide');">User Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('user-help');">Help</a></th>
</tr>
</table>

<div id="user-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Profile Form</strong>
  <ul>
    <li><strong>User Login</strong> - Used by the user to log in to the system. This can be changed but the user needs to know the new name.</li>
    <li><strong>User Access</strong> - It's best to disable a user to maintain any ownerships in the system. Change to Disabled to deny access to an account.</li>
    <li><strong>Edit Level</strong> - There are four levels. The site has restrictions for access. Most users are set to Edit mode since they have parts of the Inventory that they need to be able to edit.</li>
    <li><strong>Theme</strong> - Select a theme for the user.</li>
    <li><strong>First Name</strong> - The user's first name.</li>
    <li><strong>Last Name</strong> - The user's last name.</li>
    <li><strong>E-Mail</strong> - The user's official email address. This is important in that several email portions of the system check incoming email against this address.</li>
    <li><strong>Phone Number</strong> - The user's contact phone number. Could be desk phone or cell phone.</li>
    <li><strong>Group</strong> - The group the user belongs to. This gives the user ownership over editing equipment owned by that group.</li>
  </ul></li>
  <li><strong>Password Form</strong>
  <ul>
    <li><strong>Reset User Password</strong> - Enter in a new password for the user here.</li>
    <li><strong>Re-Enter Password</strong> - Enter the password in again. If the passwords don't match, the two boxes <span class="ui-state-error">change to indicate</span> a mismatch</li>
    <li><strong>Force Password Reset on Next Login</strong> - Check this box if you're resetting a user password or otherwise want to force a password reset.</li>
  </ul></li>
</ul>

</div>

</div>


<div id="user-hide" style="display: none">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content">
<input type="button" disabled="true" name="update" value="Update User"  onClick="javascript:attach_users('add.users.mysql.php', 1);hideDiv('user-hide')">
<input type="hidden" name="id" value="0">
<input type="button"                 name="addnew" value="Add New User" onClick="javascript:attach_users('add.users.mysql.php', 0);">
  </td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="5">Profile Form</th>
</tr>
<tr>
  <td class="ui-widget-content">User Login <input type="text" name="usr_name" size="10"></td>
  <td class="ui-widget-content">User Access <select name="usr_disabled">
<option value="0">Enabled</option>
<option value="1">Disabled</option>
</select></td>
  <td class="ui-widget-content">Edit Level <select name="usr_level">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select lvl_level,lvl_name ";
  $q_string .= "from levels ";
  $q_string .= "where lvl_disabled = 0 ";
  $q_string .= "order by lvl_level";
  $q_levels = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_levels = mysqli_fetch_array($q_levels)) {
    print "<option value=\"" . $a_levels['lvl_level'] . "\">" . $a_levels['lvl_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Theme <select name="usr_theme">
<?php
  $q_string  = "select theme_id,theme_title ";
  $q_string .= "from themes ";
  $q_string .= "order by theme_title";
  $q_themes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_themes = mysqli_fetch_array($q_themes)) {
    print "<option value=\"" . $a_themes['theme_id'] . "\">" . $a_themes['theme_title'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">First Name <input type="text" name="usr_first" size="20"></td>
  <td class="ui-widget-content">Last Name <input type="text" name="usr_last" size="20"></td>
  <td class="ui-widget-content">E-Mail <input type="text" name="usr_email" size="20"></td>
  <td class="ui-widget-content">Phone Number <input type="text" name="usr_phone" size="20"></td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="3">Password Form</th>
</tr>
<tr>
  <td class="ui-widget-content" id="password">Reset User Password <input type="password" autocomplete="false" name="usr_passwd" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" readonly onfocus="this.removeAttribute('readonly');"></td>
  <td class="ui-widget-content" id="reenter">Re-Enter Password <input type="password" autocomplete="false" value="" name="usr_reenter" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" 
onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);"></td>
  <td class="ui-widget-content">Force Password Reset on Next Login? <input type="checkbox" checked="true" name="usr_reset"></td>
</tr>
</table>

</div>

<p></p>

<div id="tabs">

<ul>
  <li><a href="#newuser">New Users</a></li>
  <li><a href="#mrjohnson">Mr. Johnson</a></li>
  <li><a href="#fixer">Fixers</a></li>
  <li><a href="#shadowrunner">Shadowrunners</a></li>
  <li><a href="#chummer">Chummers</a></li>
  <li><a href="#guest">Guests</a></li>
  <li><a href="#disabled">Disabled Users</a></li>
</ul>


<div id="newuser">

<span id="newuser_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="mrjohnson">

<span id="mrjohnson_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="fixer">

<span id="fixer_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="shadowrunner">

<span id="shadowrunner_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="chummer">

<span id="chummer_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="guest">

<span id="guest_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="disabled">

<span id="disabled_table"><?php print wait_Process('Loading Users...')?></span>

</div>


</div>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
