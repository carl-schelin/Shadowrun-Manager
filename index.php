<?php
# Script: index.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Sitepath . "/guest.php");

  $package = "index.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

#  $q_string  = "select usr_id,usr_group ";
#  $q_string .= "from users ";
#  $q_string .= "where usr_id = " . $_SESSION['uid'] . " ";
#  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
#  $a_users = mysqli_fetch_array($q_users);
#
#  $formVars['uid'] = $a_users['usr_id'];
#  $formVars['group'] = $a_users['usr_group'];


?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Mooks Manager</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script language="javascript">

function attach_group( p_script_url ) {
  var ag_form = document.index;
  var ag_url;

  ag_url  = '?group='     + ag_form.group.value;
  ag_url += '&opposed='   + ag_form.opposed.value;

  script = document.createElement('script');
  script.src = p_script_url + ag_url;
  window.location.href=script.src;
}

function attach_search( p_script_url ) {
  var as_form = document.index;
  var as_url;

  as_url  = '?search_by='     + as_form.search_by.value;
  as_url += '&search_for='    + encodeURI(as_form.search_for.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function submit_handler() {
    return (false);
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
  $( "#search-tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
 <li><a href="javascript:;" onmousedown="toggleDiv('help');">Help</a></li>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="help" style="display:none">

<div class="main-help ui-widget-content">

</div>

</div>

</div>

<form name="index" onsubmit="submit_handler();" method="GET">

<div id="main">

<div class="main-help ui-widget-content">

<p><strong>Filters</strong></p>

<?php
  print "<p>Select a Group: <select name=\"group\">\n";
  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from groups ";
  $q_string .= "left join members on members.mem_group = groups.grp_id ";
  $q_string .= "where grp_disabled = 0 and (grp_owner = " . $_SESSION['uid'] . " or mem_owner = " . $_SESSION['uid'] . ") ";
  $q_string .= "group by grp_name ";
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_groups) > 0) {
    print "<option value=\"0\">All Available Groups</option>\n";
    while ($a_groups = mysqli_fetch_array($q_groups)) {
      print "<option value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    }
  } else {
    print "<option value=\"0\">No Available Groups</option>\n";
  }
  print "</select></p>\n";
?>
<?php
  print "<p>Select an Opposed Group: <select name=\"opposed\">\n";
  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from groups ";
  $q_string .= "where grp_disabled = 0 and grp_owner = " . $_SESSION['uid'] . " ";
  $q_string .= "order by grp_name ";
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_groups) > 0) {
    print "<option value=\"-1\">No Opposition Group</option>\n";
    print "<option value=\"0\">All Available Groups</option>\n";
    while ($a_groups = mysqli_fetch_array($q_groups)) {
      print "<option value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    }
  } else {
    print "<option value=\"0\">No Available Groups</option>\n";
  }
  print "</select></p>\n";
?>

</div>

</div>

<div id="main">

<div id="tabs">

<ul>
  <li><a href="#mooks">Mooks</a></li>
  <li><a href="#reports">Reports</a></li>
  <li><a href="#tagcloud">Tag Cloud</a></li>
  <li><a href="#search">Search</a></li>
</ul>


<div id="mooks">

<ul>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/mooks.php');">Character Listing</a></li>
  <li><a href="<?php print $Reportroot; ?>/contacts.php">Contact Listing</a></li>
</ul>

</div>


<div id="reports">

<ul>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/active.php');">Active Skill Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/bioware.php');">Bioware Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/cyberware.php');">Cyberware Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/history.php');">Historical Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/magic.php');">Magical Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/qualities.php');">Qualities Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/vehicles.php');">Vehicle Listing</a></li>
  <li><a href="javascript:;" onClick="javascript:attach_group('<?php print $Reportroot; ?>/weapons.php');">Weapons Listing</a></li>
</ul>

</div>


<div id="tagcloud">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Tag Cloud</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('help-tagcloud');">Help</a></th>
</tr>
</table>

<div id="help-tagcloud" style="display:none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Tag Cloud</strong>
  <ul>
    <li><strong>Personal Tags</strong> - Shows tags that only you can manipulate. These tags are only visible to you so they let you create personalized runner lists.</li>
    <li><strong>Public Tags</strong> - Tags that are viewable by all users of the software. These tags may be useful for grouping like runners that may cross groups.</li>
  </ul></li>
</ul>

</div>

</div>

<div class="main ui-widget-content">

<t4>Personal Cloud</t4>

<ul id="cloud">
<?php
  $q_string  = "select tag_name,count(tag_name) ";
  $q_string .= "from tags ";
  $q_string .= "where tag_view = 0 and tag_owner = " . $formVars['uid'] . " ";
  $q_string .= "group by tag_name ";
  $q_tags = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_tags = mysqli_fetch_array($q_tags)) {
    $linkstart = "<a href=\"" . $Reportroot . "/tag.view.php?tag=" . $a_tags['tag_name'] . "&type=0\">";
    $linkend   = "</a>";

    print "  <li>" . $linkstart . $a_tags['tag_name'] . " (" . $a_tags['count(tag_name)'] . ")" . $linkend . "</li>\n";
  }
?>
</ul>

</div>


<div class="main ui-widget-content">

<t4>Public Cloud</t4>

<ul id="cloud">
<?php
  $q_string  = "select tag_name,count(tag_name) ";
  $q_string .= "from tags ";
  $q_string .= "where tag_view = 1 ";
  $q_string .= "group by tag_name ";
  $q_tags = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_tags = mysqli_fetch_array($q_tags)) {
    $linkstart = "<a href=\"" . $Reportroot . "/tag.view.php?tag=" . $a_tags['tag_name'] . "&type=1\">";
    $linkend   = "</a>";

    print "  <li>" . $linkstart . $a_tags['tag_name'] . " (" . $a_tags['count(tag_name)'] . ")" . $linkend . "</li>\n";
  }
?>
</ul>

</div>

</div>


<div id="search">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Search Form</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('help-search');">Help</a></th>
</tr>
</table>

<div id="help-search" style="display:none">

<div class="main-help ui-widget-content">

<p><strong>Select Search Field</strong> - Select the areas you wish to search. This will reduce the number of results and speed up the response.</p>

<p><strong>Search Criteria</strong> - Enter in the text you want to search for. Don't enter any wild cards, the search will add them for you.</p>

<p><strong>Search</strong> - Click the button when ready. A table will be displayed with the search results.</p>

<p><strong>Note:</strong> - In the Software and Hardware search tables, clicking on the Vendor, Software/Model, or Type will restart the search based on the exact text in that field. For example you can enter 'Red' in the search box to bring up everything with the word 'Red' in it (not case sensitive). Further clicking on 'Red Hat' will restart the search and return just systems associated with 'Red Hat'. This does search all three fields so searching on 'OS' might return more systems that just OS.</p>

</div>

</div>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="search_addbtn" value="Search" onClick="javascript:attach_search('<?php print $Reportroot; ?>/search.php');"></td>
</tr>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Select Search Field: <select name="search_by">
<option value="0">All Fields</option>
<option value="1">Server Names</option>
<option value="2">IP Addresses</option>
<option value="3">Software</option>
<option value="4">Hardware</option>
<option value="5">Asset/Serial/Service Tag</option>
</select></td>
  <td class="ui-widget-content">Search Criteria: <input type="text" name="search_for" size="80"></td>
</tr>
</table>

<p></p>

<div id="search-tabs">

<ul>
  <li><a href="#servername">Server Names</a></li>
  <li><a href="#ipaddr">IP Addresses</a></li>
  <li><a href="#software">Software</a></li>
  <li><a href="#asset">Hardware</a></li>
  <li><a href="#asset">Asset</a></li>
</ul>


<div id="servername">

<span id="server_search_mysql"></span>

</div>


<div id="ipaddr">

<span id="address_search_mysql"></span>

</div>


<div id="software">

<span id="software_search_mysql"></span>

</div>


<div id="hardware">

<span id="hardware_search_mysql"></span>

</div>


<div id="asset">

<span id="asset_search_mysql"></span>

</div>


</div>

</div>

</div>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
