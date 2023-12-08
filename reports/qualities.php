<?php
# Script: qualities.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "qualities.php";

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
<title>Qualities Listing</title>

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
    $a_groups = mysqli_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">" . $groupname . "Qualities Information</th>\n";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('qualities-help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"qualities-help\" style=\"display: none\">\n";

  print "<div class=\"main-help ui-widget-content\">\n";

  print "<p>Help</p>\n";

  print "</div>\n";

  print "</div>\n";


  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Owner</th>\n";
  print "  <th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print "  <th class=\"ui-state-default\">Name</th>\n";
  print "  <th class=\"ui-state-default\">Value</th>\n";
  print "  <th class=\"ui-state-default\">Description</th>\n";
  print "  <th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>";

  $q_string  = "select r_qual_id,r_qual_character,qual_id,qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details,";
  $q_string .= "usr_last,usr_first,runr_name,runr_archetype ";
  $q_string .= "from r_qualities ";
  $q_string .= "left join runners on runners.runr_id = r_qualities.r_qual_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
  $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
  $q_string .= "order by runr_name,qual_value desc,qual_name ";
  $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_qualities) > 0) {
    while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_qualities['r_qual_character'] . " ";
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
        if (check_owner($a_r_qualities['r_qual_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_qualities['r_qual_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $details = '';
        if (strlen($a_r_qualities['r_qual_details']) > 0) {
          $details = " (" . $a_r_qualities['r_qual_details'] . ")";
        }

        $qual_book = return_Book($a_r_qualities['ver_book'], $a_r_qualities['qual_page']);

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_qualities['usr_first'] . " " . $a_r_qualities['usr_last'] . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_qualities['runr_name'] . " (" . $a_r_qualities['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_qualities['qual_name'] . $details . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_qualities['qual_value']           . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_qualities['qual_desc']            . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $qual_book                             . "</td>\n";
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
      $a_groups = mysqli_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\">" . $groupname . "Qualities Information</th>\n";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('qualities-help');\">Help</a></th>\n";
    print "</tr>\n";
    print "</table>\n";

    print "<div id=\"qualities-help\" style=\"display: none\">\n";

    print "<div class=\"main-help ui-widget-content\">\n";

    print "<p>Help</p>\n";

    print "</div>\n";

    print "</div>\n";

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Owner</th>\n";
    print "  <th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print "  <th class=\"ui-state-default\">Name</th>\n";
    print "  <th class=\"ui-state-default\">Value</th>\n";
    print "  <th class=\"ui-state-default\">Description</th>\n";
    print "  <th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>";

    $q_string  = "select r_qual_id,r_qual_character,qual_id,qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details,";
    $q_string .= "usr_last,usr_first,runr_name,runr_archetype ";
    $q_string .= "from r_qualities ";
    $q_string .= "left join runners on runners.runr_id = r_qualities.r_qual_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
    $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
    $q_string .= "order by runr_name,qual_value desc,qual_name ";
    $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_qualities) > 0) {
      while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_qualities['r_qual_character'] . " ";
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
          if (check_owner($a_r_qualities['r_qual_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_qualities['r_qual_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $details = '';
          if (strlen($a_r_qualities['r_qual_details']) > 0) {
            $details = " (" . $a_r_qualities['r_qual_details'] . ")";
          }

          $qual_book = return_Book($a_r_qualities['ver_book'], $a_r_qualities['qual_page']);

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_qualities['usr_first'] . " " . $a_r_qualities['usr_last'] . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_qualities['runr_name'] . " (" . $a_r_qualities['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_qualities['qual_name'] . $details . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_qualities['qual_value']           . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_qualities['qual_desc']            . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $qual_book                             . "</td>\n";
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
