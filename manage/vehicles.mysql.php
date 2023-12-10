<?php
# Script: vehicles.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  $package = "vehicles.mysql.php";
  $formVars['id'] = clean($_GET['id'], 10);

  logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

  $q_string  = "select ver_version ";
  $q_string .= "from versions ";
  $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_versions = mysqli_fetch_array($q_versions);

  $output  = "<p></p>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#vehicles\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Vehicle Information";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('vehicles-listing-help');\">Help</a></th>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<div id=\"vehicles-listing-help\" style=\"display: none\">\n";

  $output .= "<div class=\"main-help ui-widget-content\">\n";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Spell Listing</strong>\n";
  $output .= "  <ul>\n";
  $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
  $output .= "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
  $output .= "  </ul></li>\n";
  $output .= "</ul>\n";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Notes</strong>\n";
  $output .= "  <ul>\n";
  $output .= "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
  $output .= "  </ul></li>\n";
  $output .= "</ul>\n";

  $output .= "</div>\n";

  $output .= "</div>\n";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Class</th>\n";
  $output .=   "<th class=\"ui-state-default\">Type</th>\n";
  $output .=   "<th class=\"ui-state-default\">Make</th>\n";
  $output .=   "<th class=\"ui-state-default\">Model</th>\n";
  $output .=   "<th class=\"ui-state-default\">Handling</th>\n";
  $output .=   "<th class=\"ui-state-default\">Acceleration</th>\n";
  if ($a_versions['ver_version'] == 6.0) {
    $output .=   "<th class=\"ui-state-default\">Interval</th>\n";
  }
  $output .=   "<th class=\"ui-state-default\">Speed</th>\n";
  $output .=   "<th class=\"ui-state-default\">Body</th>\n";
  $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
  $output .=   "<th class=\"ui-state-default\">Pilot</th>\n";
  $output .=   "<th class=\"ui-state-default\">Sensor</th>\n";
  $output .=   "<th class=\"ui-state-default\">Seats</th>\n";
  $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
  $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
  $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $totalcost = 0;
  $q_string  = "select r_veh_id,class_name,veh_type,veh_make,veh_model,veh_onacc,veh_offacc,veh_onhand,";
  $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_interval,veh_pilot,veh_body,veh_armor,veh_sensor,";
  $q_string .= "veh_onseats,veh_offseats,veh_avail,veh_perm,veh_cost,ver_book,veh_page ";
  $q_string .= "from r_vehicles ";
  $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
  $q_string .= "left join class on class.class_id = vehicles.veh_class ";
  $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
  $q_string .= "where r_veh_character = " . $formVars['id'] . " ";
  $q_string .= "order by veh_class,veh_type,veh_make ";
  $q_r_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_vehicles) > 0) {
    while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {

      $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

      $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

      $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

      $veh_seats = return_Seats($a_r_vehicles['veh_onseats'], $a_r_vehicles['veh_offseats']);

      $veh_avail = return_Avail($a_r_vehicles['veh_avail'], $a_r_vehicles['veh_perm']);

      $totalcost += $a_r_vehicles['veh_cost'];
      $veh_cost = return_Cost($a_r_vehicles['veh_cost']);

      $veh_book = return_Book($a_r_vehicles['ver_book'], $a_r_vehicles['veh_page']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['class_name']   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_vehicles['veh_type']     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_make']     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_model']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_handling                 . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_acceleration             . "</td>\n";
      if ($a_versions['ver_version'] == 6.0) {
        $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_interval'] . "</td>\n";
      }
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_speed                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_body']     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_armor']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_pilot']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_vehicles['veh_sensor']   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_seats                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_avail                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_cost                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $veh_book                     . "</td>\n";
      $output .= "</tr>\n";

      $output .= "<tr>\n";
      $a_veh_damage = intval(($a_r_vehicles['veh_body'] + .5) / 2) + 8;
      $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">" . "Damage: (" . $a_veh_damage . "): ";
      for ($i = 0; $i < 18; $i++) {
        if ($a_veh_damage > $i) {
          $disabled = "";
        } else {
          $disabled = "disabled=\"true\"";
        }

        $output .= "<input type=\"checkbox\" " . $disabled . ">\n";
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";

    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"19\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";
  } else {
    $output .= "  <td class=\"ui-widget-content\" colspan=\"19\">No Vehicles added.</td>\n";
  }
  $output .= "</table>\n";

  mysqli_free_result($q_r_vehicles);

  print "document.getElementById('vehicles_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

?>
