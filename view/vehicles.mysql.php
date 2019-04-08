<?php
# Script: vehicles.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "vehicles.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Vehicles</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Vehicle</th>\n";
  $output .=   "<th class=\"ui-state-default\">Handling</th>\n";
  $output .=   "<th class=\"ui-state-default\">Speed</th>\n";
  $output .=   "<th class=\"ui-state-default\">Acceleration</th>\n";
  $output .=   "<th class=\"ui-state-default\">Body</th>\n";
  $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
  $output .=   "<th class=\"ui-state-default\">Pilot</th>\n";
  $output .=   "<th class=\"ui-state-default\">Sensor</th>\n";
  $output .=   "<th class=\"ui-state-default\">Seats</th>\n";
  $output .= "</tr>\n";

  $nuyen = '&yen;';
  $q_string  = "select r_veh_id,r_veh_conmon,veh_make,veh_model,veh_onacc,veh_offacc,veh_onhand,";
  $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_pilot,veh_body,veh_armor,veh_sensor,";
  $q_string .= "veh_onseats,veh_offseats ";
  $q_string .= "from r_vehicles ";
  $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
  $q_string .= "where r_veh_character = " . $formVars['id'] . " ";
  $q_string .= "order by veh_class,veh_type,veh_make ";
  $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_vehicles) > 0) {
    while ($a_r_vehicles = mysql_fetch_array($q_r_vehicles)) {

      $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

      $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

      $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

      $veh_seats = return_Seats($a_r_vehicles['veh_onseats'], $a_r_vehicles['veh_offseats']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"                     . $a_r_vehicles['veh_make'] . " " . $a_r_vehicles['veh_model']   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $veh_handling                                                  . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $veh_speed                                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $veh_acceleration                                              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['veh_body']                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['veh_armor']                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['veh_pilot']                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['veh_sensor']                                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"              . $veh_seats                                                     . "</td>\n";
      $output .= "</tr>\n";

      $output .= "<tr>\n";
      $vehicle_damage = ceil(($a_r_vehicles['veh_body'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">" . "Vehicle Damage: (" . $a_veh_damage . "): ";
      for ($i = 1; $i <= 18; $i++) {
        if ($vehicle_damage >= $i) {
          $checked = '';
          if ($i <= $a_r_vehicles['r_veh_conmon']) {
            $checked = 'checked=\"true\"';
          }

          $output .= "<input type=\"checkbox\" " . $checked . " id=\"vehcon" . ${i} . "\"  onclick=\"edit_VehicleCondition(" . ${i} . ", " . $a_r_vehicles['r_veh_id'] . ", 'vehicle');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_veh_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where sub_name = \"Vehicles\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

          $acc_name = $a_r_accessory['acc_name'];
          if ($a_r_accessory['acc_mount'] != '') {
            $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
          }

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "</tr>\n";
        }
      }



# now display the attached firearms
      $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
      $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
      $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
      $q_string .= "from r_firearms ";
      $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
      $q_string .= "left join class on class.class_id = firearms.fa_class ";
      $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
      $q_string .= "where r_fa_character = " . $formVars['id'] . " and r_fa_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
      $q_string .= "order by fa_name,fa_class ";
      $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_firearms) > 0) {
        while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_firearms['fa_name']                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
          $output .= "</tr>\n";



# associate any ammo with the weapon
          $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
          $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
          $q_string .= "from r_ammo ";
          $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
          $q_string .= "left join class on class.class_id = ammo.ammo_class ";
          $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
          $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by ammo_name,class_name ";
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

              $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

              $class = "ui-widget-content";

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt;&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "</tr>\n";

            }
          }
        }
      }


# associate any ammo with the vehicle
      $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
      $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
      $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentveh = " . $a_r_vehicles['r_veh_id'] . " ";
      $q_string .= "order by ammo_name,class_name ";
      $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "</tr>\n";

        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('vehicles_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
