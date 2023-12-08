<?php
# Script: vehicles.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "vehicles.php";

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
<title>Vehicle Listing</title>

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
    $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_groups = mysqli_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<p></p>\n";
  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">" . $groupname . "Vehicle Information</th>\n";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('vehicles-listing-help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"vehicles-listing-help\" style=\"display: none\">\n";

  print "<div class=\"main-help ui-widget-content\">\n";

  print "<ul>\n";
  print "  <li><strong>Spell Listing</strong>\n";
  print "  <ul>\n";
  print "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
  print "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
  print "  </ul></li>\n";
  print "</ul>\n";

  print "<ul>\n";
  print "  <li><strong>Notes</strong>\n";
  print "  <ul>\n";
  print "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
  print "  </ul></li>\n";
  print "</ul>\n";

  print "</div>\n";

  print "</div>\n";

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print   "<th class=\"ui-state-default\">Owner</th>\n";
  print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print   "<th class=\"ui-state-default\">Class</th>\n";
  print   "<th class=\"ui-state-default\">Type</th>\n";
  print   "<th class=\"ui-state-default\">Vehicle</th>\n";
  print   "<th class=\"ui-state-default\">Handling</th>\n";
  print   "<th class=\"ui-state-default\">Speed</th>\n";
  print   "<th class=\"ui-state-default\">Acceleration</th>\n";
  print   "<th class=\"ui-state-default\">Body</th>\n";
  print   "<th class=\"ui-state-default\">Armor</th>\n";
  print   "<th class=\"ui-state-default\">Pilot</th>\n";
  print   "<th class=\"ui-state-default\">Sensor</th>\n";
  print   "<th class=\"ui-state-default\">Seats</th>\n";
  print   "<th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select r_veh_id,r_veh_character,class_name,veh_class,veh_type,veh_make,veh_model,veh_onacc,veh_offacc,veh_onhand,";
  $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_pilot,veh_body,veh_armor,veh_sensor,";
  $q_string .= "veh_onseats,veh_offseats,usr_last,usr_first,runr_name,runr_archetype,ver_book,veh_page ";
  $q_string .= "from r_vehicles ";
  $q_string .= "left join runners on runners.runr_id = r_vehicles.r_veh_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
  $q_string .= "left join class on class.class_id = vehicles.veh_class ";
  $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
  $q_string .= "order by runr_name,veh_class,veh_type,veh_make ";
  $q_r_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_vehicles) > 0) {
    while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {

      $display = "No"; 

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_vehicles['r_veh_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes"; 
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_vehicles['r_veh_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_vehicles['r_veh_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {
        $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

        $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

        $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

        $veh_seats = return_Seats($a_r_vehicles['veh_onseats'], $a_r_vehicles['veh_offseats']);

        $book = return_Book($a_r_vehicles['ver_book'], $a_r_vehicles['veh_page']);

        print "<tr>\n";
        print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['usr_first'] . " " . $a_r_vehicles['usr_last']   . "</td>\n";
        print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['runr_name'] . " (" . $a_r_vehicles['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['class_name']                                    . "</td>\n";
        print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_type']                                      . "</td>\n";
        print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_make'] . " " . $a_r_vehicles['veh_model']   . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $veh_handling                                                  . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $veh_speed                                                     . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $veh_acceleration                                              . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_body']                                      . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_armor']                                     . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_pilot']                                     . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_sensor']                                    . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $veh_seats                                                     . "</td>\n";
        print "  <td class=\"ui-widget-content delete\">" . $book                                                          . "</td>\n";
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
      $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysqli_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<p></p>\n";
    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\">" . $groupname . "Vehicle Information</th>\n";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('opposed-listing-help');\">Help</a></th>\n";
    print "</tr>\n";
    print "</table>\n";

    print "<div id=\"opposed-listing-help\" style=\"display: none\">\n";

    print "<div class=\"main-help ui-widget-content\">\n";

    print "<ul>\n";
    print "  <li><strong>Spell Listing</strong>\n";
    print "  <ul>\n";
    print "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
    print "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
    print "  </ul></li>\n";
    print "</ul>\n";

    print "<ul>\n";
    print "  <li><strong>Notes</strong>\n";
    print "  <ul>\n";
    print "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
    print "  </ul></li>\n";
    print "</ul>\n";

    print "</div>\n";

    print "</div>\n";

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print   "<th class=\"ui-state-default\">Owner</th>\n";
    print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print   "<th class=\"ui-state-default\">Class</th>\n";
    print   "<th class=\"ui-state-default\">Type</th>\n";
    print   "<th class=\"ui-state-default\">Vehicle</th>\n";
    print   "<th class=\"ui-state-default\">Handling</th>\n";
    print   "<th class=\"ui-state-default\">Speed</th>\n";
    print   "<th class=\"ui-state-default\">Acceleration</th>\n";
    print   "<th class=\"ui-state-default\">Body</th>\n";
    print   "<th class=\"ui-state-default\">Armor</th>\n";
    print   "<th class=\"ui-state-default\">Pilot</th>\n";
    print   "<th class=\"ui-state-default\">Sensor</th>\n";
    print   "<th class=\"ui-state-default\">Seats</th>\n";
    print   "<th class=\"ui-state-default\">Availability</th>\n";
    print "</tr>\n";

    $q_string  = "select r_veh_id,r_veh_character,veh_class,veh_type,veh_make,veh_model,veh_onacc,veh_offacc,veh_onhand,";
    $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_pilot,veh_body,veh_armor,veh_sensor,";
    $q_string .= "veh_onseats,veh_offseats,veh_avail,veh_perm,usr_last,usr_first,runr_name,runr_archetype ";
    $q_string .= "from r_vehicles ";
    $q_string .= "left join runners on runners.runr_id = r_vehicles.r_veh_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
    $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
    $q_string .= "order by runr_name,veh_class,veh_type,veh_make ";
    $q_r_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_vehicles) > 0) {
      while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {

        $display = "No"; 

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_vehicles['r_veh_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes"; 
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_vehicles['r_veh_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_vehicles['r_veh_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {
          $veh_avail = $a_r_vehicles['veh_avail'] . $a_r_vehicles['veh_perm'];

          if ($a_r_vehicles['veh_offhand'] > 0) {
            $veh_handling = $a_r_vehicles['veh_onhand'] . "/" . $a_r_vehicles['veh_offhand'];
          } else {
            $veh_handling = $a_r_vehicles['veh_onhand'];
          }

          if ($a_r_vehicles['veh_offspeed'] > 0) {
            $veh_speed = $a_r_vehicles['veh_onspeed'] . "/" . $a_r_vehicles['veh_offspeed'];
          } else {
            $veh_speed = $a_r_vehicles['veh_onspeed'];
          }

          if ($a_r_vehicles['veh_offacc'] > 0) {
            $veh_acceleration = $a_r_vehicles['veh_onacc'] . "/" . $a_r_vehicles['veh_offacc'];
          } else {
            $veh_acceleration = $a_r_vehicles['veh_onacc'];
          }

          if ($a_r_vehicles['veh_offseats'] > 0) {
            $veh_seats = $a_r_vehicles['veh_onseats'] . "/" . $a_r_vehicles['veh_offseats'];
          } else {
            $veh_seats = $a_r_vehicles['veh_onseats'];
          }

          print "<tr>\n";
          print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['usr_first'] . " " . $a_r_vehicles['usr_last']   . "</td>\n";
          print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['runr_name'] . " (" . $a_r_vehicles['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_class']                                     . "</td>\n";
          print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_type']                                      . "</td>\n";
          print "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_make'] . " " . $a_r_vehicles['veh_model']   . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $veh_handling                                                  . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $veh_speed                                                     . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $veh_acceleration                                              . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_body']                                      . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_armor']                                     . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_pilot']                                     . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_sensor']                                    . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $veh_seats                                                     . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $veh_avail                                                     . "</td>\n";
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
