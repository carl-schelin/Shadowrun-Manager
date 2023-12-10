<?php
# Script: users.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Guest);

  $package = "users.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Yourself</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

function attach_users(p_script_url, update) {
  var au_form = document.user;
  var au_url;

  au_url  = '?update='   + update;

  au_url += "&usr_first="      + encode_URI(au_form.usr_first.value);
  au_url += "&usr_last="       + encode_URI(au_form.usr_last.value);
  au_url += "&usr_email="      + encode_URI(au_form.usr_email.value);
  au_url += "&usr_phone="      + encode_URI(au_form.usr_phone.value);
  au_url += "&usr_theme="      + au_form.usr_theme.value;
  au_url += "&usr_passwd="     + encode_URI(au_form.usr_passwd.value);
  au_url += "&usr_reenter="    + encode_URI(au_form.usr_reenter.value);

  script = document.createElement('script');
  script.src = p_script_url + au_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('users.fill.php');
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

<div class="main">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">User Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('user-help');">Help</a></th>
</tr>
</table>

<div id="user-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Profile Form</strong>
  <ul>
    <li><strong>First Name</strong> - Your first name.</li>
    <li><strong>Last Name</strong> - Your last name.</li>
    <li><strong>E-Mail</strong> - Your email address.</li>
    <li><strong>Phone Number</strong> - Your phone number.</li>
    <li><strong>Theme</strong> - Your theme.</li>
  </ul></li>
  <li><strong>Password Form</strong>
  <ul>
    <li><strong>Reset User Password</strong> - Enter in a new password.</li>
    <li><strong>Re-Enter Password</strong> - Enter the password in again. If the passwords don't match, the two boxes <span class="ui-state-highlight">change to indicate</span> a mismatch</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content">
<input type="button" disabled="true" name="update" value="Update"  onClick="javascript:attach_users('users.mysql.php', 1);">
  </td>
</tr>
</table>

<p></p>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="3">Profile Form</th>
</tr>
<tr>
  <td class="ui-widget-content">First Name <input type="text" name="usr_first" size="20"></td>
  <td class="ui-widget-content">Last Name <input type="text" name="usr_last" size="20"></td>
  <td class="ui-widget-content">E-Mail <input type="email" name="usr_email" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Phone Number <input type="phone" name="usr_phone" size="20"></td>
  <td class="ui-widget-content" colspan="3">Theme <select name="usr_theme">
<?php
  $q_string  = "select theme_id,theme_title ";
  $q_string .= "from themes ";
  $q_string .= "order by theme_title";
  $q_themes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_themes = mysqli_fetch_array($q_themes)) {
    print "<option value=\"" . $a_themes['theme_id'] . "\">" . $a_themes['theme_title'] . "</option>\n";
  }
?>
</select></td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="2">Password Form</th>
</tr>
<tr>
  <td class="ui-widget-content" id="password">Reset User Password <input type="password" autocomplete="off" name="usr_passwd" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);"></td>
  <td class="ui-widget-content" id="reenter">Re-Enter Password <input type="password" name="usr_reenter" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" 
onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);"></td>
</tr>
</table>

</div>


<div class="main">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Invitation Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('invite-help');">Help</a></th>
</tr>
</table>

<div id="invite-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Invitation Management</strong>
  <ul>
    <li><strong>Delete</strong> - Delete this request. It'll disappear from the organizer's listing without notice.</li>
    <li><strong>Accept</strong> - Accept the invitation to the game.</li>
    <li><strong>Decline</strong> - Decline the invitation to the game.</li>
    <li><strong>Group</strong> - The name of the group you're being invited to.</li>
    <li><strong>Group Owner</strong> - The owner of the group you're being invited to.</li>
    <li><strong>Runner</strong> - The name of the Runner being invited.</li>
    <li><strong>Visibility</strong> - What to let other group members see.
    <ul>
      <li><strong>Unchecked</strong> - "Coworkers" - Just what is visible in day to day interactions or on the job.</li>
      <li><strong>Checked</strong> - "Friends" - Invited over to your pad, meets other friends, shows off gun collection.</li>
    </ul></li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="7">Invite Listing</th>
</tr>
<tr>
  <th class="ui-state-default">Delete</th>
  <th class="ui-state-default">Accept</th>
  <th class="ui-state-default">Decline</th>
  <th class="ui-state-default">Group</th>
  <th class="ui-state-default">Group Owner</th>
  <th class="ui-state-default">Runner</th>
  <th class="ui-state-default">Visibility</th>
</tr>
<?php

  $q_string  = "select mem_id,runr_name,grp_name,usr_last,usr_first,mem_invite,mem_visible ";
  $q_string .= "from members ";
  $q_string .= "left join runners on runners.runr_id = members.mem_runner ";
  $q_string .= "left join groups on groups.grp_id = members.mem_group ";
  $q_string .= "left join users on users.usr_id = groups.grp_owner ";
  $q_string .= "where runr_owner = " . $_SESSION['uid'] . " ";
  $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_members = mysqli_fetch_array($q_members)) {

    $delete  = "<input type=\"button\" value=\"Delete\" onClick=\"javascript:show_file('members.del.php?id="   . $a_members['mem_id'] . "');\">";

    $disabled = '';
    if ($a_members['mem_invite'] == 1) {
      $disabled = "disabled=\"true\" ";
    }
    $accept  = "<input type=\"button\" " . $disabled . "name=\"btnaccept\" value=\"Accept\"  onClick=\"javascript:show_file('members.mysql.php?mem_id=" . $a_members['mem_id'] . "&status=1');\">";

    $disabled = '';
    if ($a_members['mem_invite'] == 0) {
      $disabled = "disabled=\"true\" ";
    }
    $decline = "<input type=\"button\" " . $disabled . "name=\"btndecline\" value=\"Decline\" onClick=\"javascript:show_file('members.mysql.php?mem_id=" . $a_members['mem_id'] . "&status=0');\">";

    $checked = "";
    if ($a_members['mem_visible']) {
      $checked = "checked ";
    }
    $visibility = "Visibility On? <input type=\"checkbox\" " . $checked . "onClick=\"javascript:show_file('members.checked.php?mem_id=" . $a_members['mem_id'] . "');\">";

    print "<tr>\n";
    print "  <td class=\"ui-widget-content delete\">" . $delete                                                . "</td>\n";
    print "  <td class=\"ui-widget-content delete\">" . $accept                                                . "</td>\n";
    print "  <td class=\"ui-widget-content delete\">" . $decline                                               . "</td>\n";
    print "  <td class=\"ui-widget-content\">"        . $a_members['grp_name']                                 . "</td>\n";
    print "  <td class=\"ui-widget-content\">"        . $a_members['usr_first'] . " " . $a_members['usr_last'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">"        . $a_members['runr_name']                                . "</td>\n";
    print "  <td class=\"ui-widget-content\">"        . $visibility                                            . "</td>\n";
    print "</tr>\n";

  }
?>
</table>

</div>


</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
