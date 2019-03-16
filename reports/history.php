<?php
# Script: history.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "history.php";

  logaccess($formVars['username'], $package, "Accessing the script");

  $formVars['group'] = 0;
  if (isset($_GET['group'])) {
    $formVars['group'] = clean($_GET['group'], 10);
  }
  $formVars['opposed'] = 0;
  if (isset($_GET['opposed'])) {
    $formVars['opposed'] = clean($_GET['opposed'], 10);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>History Listing</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function () {
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<?php
  if ($formVars['group'] != '') {
    $q_string  = "select grp_name ";
    $q_string .= "from groups ";
    $q_string .= "where grp_id = " . $formVars['group'] . " ";
    $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_groups = mysql_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">" . $groupname . "Historical Information</th>\n";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('history-help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"history-help\" style=\"display: none\">\n";

  print "<div class=\"main-help ui-widget-content\">\n";

  print "<p>Help</p>\n";

  print "</div>\n";

  print "</div>\n";


  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Owner</th>\n";
  print "  <th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print "  <th class=\"ui-state-default\">Date</th>\n";
  print "  <th class=\"ui-state-default\">Historical Entry</th>\n";
  print "</tr>";

  $q_string  = "select usr_first,usr_last,note_user,note_date,note_notes ";
  $q_string .= "from group_notes ";
  $q_string .= "left join users on users.usr_id = group_notes.note_user ";
  $q_string .= "where note_group = " . $formVars['group'] . " ";
  $q_string .= "order by usr_last,usr_first,note_date ";
  $q_group_notes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_group_notes) > 0) {
    while ($a_group_notes = mysql_fetch_array($q_group_notes)) {

      $display = "No";

# I'm a Johnson, show me everyone in the group
      if (check_userlevel(1)) {
        $display = 'Yes';
      }
# are we a gm for this group and the character is available for running?
      if (check_userlevel(2)) {
        $display = 'Yes';
      }

      if ($display == 'Yes') {

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_group_notes['usr_first'] . " " . $a_group_notes['usr_last'] . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . "Game Master" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_group_notes['note_date']            . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_group_notes['note_notes']            . "</td>\n";
        print "</tr>\n";
      }
    }
  }

  $q_string  = "select usr_first,usr_last,runr_name,runr_archetype,his_character,his_date,his_notes ";
  $q_string .= "from history ";
  $q_string .= "left join runners on runners.runr_id = history.his_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "order by runr_name,his_date ";
  $q_history = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_history) > 0) {
    while ($a_history = mysql_fetch_array($q_history)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_history['his_character'] . " ";
        $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_history['his_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_history['his_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_history['usr_first'] . " " . $a_history['usr_last'] . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_history['runr_name'] . " (" . $a_history['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_history['his_date']            . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_history['his_notes']            . "</td>\n";
        print "</tr>\n";
      }
    }
  }

  print "</table>\n";

?>

</div>


<?php

  if ($formVars['opposed'] > -1) {

    print "<div class=\"main\">\n";

    if ($formVars['opposed'] != '') {
      $q_string  = "select grp_name ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['opposed'] . " ";
      $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysql_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\">" . $groupname . "Historical Information</th>\n";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('opposed-help');\">Help</a></th>\n";
    print "</tr>\n";
    print "</table>\n";

    print "<div id=\"opposed-help\" style=\"display: none\">\n";

    print "<div class=\"main-help ui-widget-content\">\n";

    print "<p>Help</p>\n";

    print "</div>\n";

    print "</div>\n";

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Owner</th>\n";
    print "  <th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print "  <th class=\"ui-state-default\">Date</th>\n";
    print "  <th class=\"ui-state-default\">Historical Entry</th>\n";
    print "</tr>";

    $q_string  = "select usr_last,usr_first.runr_name,runr_archetype,his_character,his_date,his_notes ";
    $q_string .= "from history ";
    $q_string .= "left join runners on runners.runr_id = history.his_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "order by runr_name,his_date ";
    $q_history = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_history) > 0) {
      while ($a_history = mysql_fetch_array($q_history)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_history['his_character'] . " ";
          $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_history['his_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_history['his_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_history['usr_first'] . " " . $a_history['usr_last'] . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_history['runr_name'] . " (" . $a_history['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_history['his_date'] . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_history['his_notes']           . "</td>\n";
          print "</tr>\n";
        }
      }
    }

    print "</table>\n";

    print "</div>\n";

  }
?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
