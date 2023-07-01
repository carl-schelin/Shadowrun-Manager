<?php
# Script: add.vehicles.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.vehicles.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],               10);
        $formVars['veh_class']        = clean($_GET['veh_class'],        40);
        $formVars['veh_type']         = clean($_GET['veh_type'],         40);
        $formVars['veh_make']         = clean($_GET['veh_make'],         40);
        $formVars['veh_model']        = clean($_GET['veh_model'],        40);
        $formVars['veh_onhand']       = clean($_GET['veh_onhand'],       10);
        $formVars['veh_offhand']      = clean($_GET['veh_offhand'],      10);
        $formVars['veh_interval']     = clean($_GET['veh_interval'],     10);
        $formVars['veh_onspeed']      = clean($_GET['veh_onspeed'],      10);
        $formVars['veh_offspeed']     = clean($_GET['veh_offspeed'],     10);
        $formVars['veh_onacc']        = clean($_GET['veh_onacc'],        10);
        $formVars['veh_offacc']       = clean($_GET['veh_offacc'],       10);
        $formVars['veh_pilot']        = clean($_GET['veh_pilot'],        10);
        $formVars['veh_body']         = clean($_GET['veh_body'],         10);
        $formVars['veh_armor']        = clean($_GET['veh_armor'],        10);
        $formVars['veh_sensor']       = clean($_GET['veh_sensor'],       10);
        $formVars['veh_sig']          = clean($_GET['veh_sig'],          10);
        $formVars['veh_nav']          = clean($_GET['veh_nav'],          10);
        $formVars['veh_cargo']        = clean($_GET['veh_cargo'],        10);
        $formVars['veh_load']         = clean($_GET['veh_load'],         10);
        $formVars['veh_hardpoints']   = clean($_GET['veh_hardpoints'],   10);
        $formVars['veh_firmpoints']   = clean($_GET['veh_firmpoints'],   10);
        $formVars['veh_onseats']      = clean($_GET['veh_onseats'],      10);
        $formVars['veh_offseats']     = clean($_GET['veh_offseats'],     10);
        $formVars['veh_avail']        = clean($_GET['veh_avail'],        10);
        $formVars['veh_perm']         = clean($_GET['veh_perm'],         10);
        $formVars['veh_basetime']     = clean($_GET['veh_basetime'],     10);
        $formVars['veh_duration']     = clean($_GET['veh_duration'],     10);
        $formVars['veh_index']        = clean($_GET['veh_index'],        10);
        $formVars['veh_cost']         = clean($_GET['veh_cost'],         10);
        $formVars['veh_book']         = clean($_GET['veh_book'],         10);
        $formVars['veh_page']         = clean($_GET['veh_page'],         10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['veh_onhand'] == '') {
          $formVars['veh_onhand'] = 0;
        }
        if ($formVars['veh_offhand'] == '') {
          $formVars['veh_offhand'] = 0;
        }
        if ($formVars['veh_interval'] == '') {
          $formVars['veh_interval'] = 0;
        }
        if ($formVars['veh_onspeed'] == '') {
          $formVars['veh_onspeed'] = 0;
        }
        if ($formVars['veh_offspeed'] == '') {
          $formVars['veh_offspeed'] = 0;
        }
        if ($formVars['veh_onacc'] == '') {
          $formVars['veh_onacc'] = 0;
        }
        if ($formVars['veh_offacc'] == '') {
          $formVars['veh_offacc'] = 0;
        }
        if ($formVars['veh_pilot'] == '') {
          $formVars['veh_pilot'] = 0;
        }
        if ($formVars['veh_body'] == '') {
          $formVars['veh_body'] = 0;
        }
        if ($formVars['veh_armor'] == '') {
          $formVars['veh_armor'] = 0;
        }
        if ($formVars['veh_sensor'] == '') {
          $formVars['veh_sensor'] = 0;
        }
        if ($formVars['veh_sig'] == '') {
          $formVars['veh_sig'] = 0;
        }
        if ($formVars['veh_nav'] == '') {
          $formVars['veh_nav'] = 0;
        }
        if ($formVars['veh_cargo'] == '') {
          $formVars['veh_cargo'] = 0;
        }
        if ($formVars['veh_load'] == '') {
          $formVars['veh_load'] = 0;
        }
        if ($formVars['veh_hardpoints'] == '') {
          $formVars['veh_hardpoints'] = 0;
        }
        if ($formVars['veh_firmpoints'] == '') {
          $formVars['veh_firmpoints'] = 0;
        }
        if ($formVars['veh_onseats'] == '') {
          $formVars['veh_onseats'] = 0;
        }
        if ($formVars['veh_offseats'] == '') {
          $formVars['veh_offseats'] = 0;
        }
        if ($formVars['veh_avail'] == '') {
          $formVars['veh_avail'] = 0;
        }
        if ($formVars['veh_basetime'] == '') {
          $formVars['veh_basetime'] = 0;
        }
        if ($formVars['veh_index'] == '') {
          $formVars['veh_index'] = 0.00;
        }
        if ($formVars['veh_cost'] == '') {
          $formVars['veh_cost'] = 0;
        }
        if ($formVars['veh_page'] == '') {
          $formVars['veh_page'] = 0;
        }

        if (strlen($formVars['veh_model']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "veh_class        = \"" . $formVars['veh_class']        . "\"," .
            "veh_type         = \"" . $formVars['veh_type']         . "\"," .
            "veh_make         = \"" . $formVars['veh_make']         . "\"," .
            "veh_model        = \"" . $formVars['veh_model']        . "\"," .
            "veh_onhand       =   " . $formVars['veh_onhand']       . "," .
            "veh_offhand      =   " . $formVars['veh_offhand']      . "," .
            "veh_interval     =   " . $formVars['veh_interval']     . "," .
            "veh_onspeed      =   " . $formVars['veh_onspeed']      . "," .
            "veh_offspeed     =   " . $formVars['veh_offspeed']     . "," .
            "veh_onacc        =   " . $formVars['veh_onacc']        . "," .
            "veh_offacc       =   " . $formVars['veh_offacc']       . "," .
            "veh_pilot        =   " . $formVars['veh_pilot']        . "," .
            "veh_body         =   " . $formVars['veh_body']         . "," .
            "veh_armor        =   " . $formVars['veh_armor']        . "," .
            "veh_sensor       =   " . $formVars['veh_sensor']       . "," .
            "veh_sig          =   " . $formVars['veh_sig']          . "," .
            "veh_nav          =   " . $formVars['veh_nav']          . "," .
            "veh_cargo        =   " . $formVars['veh_cargo']        . "," .
            "veh_load         =   " . $formVars['veh_load']         . "," .
            "veh_hardpoints   =   " . $formVars['veh_hardpoints']   . "," .
            "veh_firmpoints   =   " . $formVars['veh_firmpoints']   . "," .
            "veh_onseats      =   " . $formVars['veh_onseats']      . "," .
            "veh_offseats     =   " . $formVars['veh_offseats']     . "," .
            "veh_avail        =   " . $formVars['veh_avail']        . "," .
            "veh_perm         = \"" . $formVars['veh_perm']         . "\"," .
            "veh_basetime     =   " . $formVars['veh_basetime']     . "," .
            "veh_duration     =   " . $formVars['veh_duration']     . "," .
            "veh_index        =   " . $formVars['veh_index']        . "," .
            "veh_cost         =   " . $formVars['veh_cost']         . "," .
            "veh_book         = \"" . $formVars['veh_book']         . "\"," .
            "veh_page         =   " . $formVars['veh_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into vehicles set veh_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update vehicles set " . $q_string . " where veh_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['veh_model']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $vehicle_list = array("aircraft", "groundcraft", "ldrones", "mdrones", "odrones", "idrones", "sdrones", "watercraft");

      foreach ($vehicle_list as &$vehicle) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Vehicles Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $vehicle . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $vehicle . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Vehicles Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Vehicle from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Vehicle to toggle the form and edit the Vehicle.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Vehicles Management</strong> title bar to toggle the <strong>Vehicles Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Type</th>\n";
        $output .=   "<th class=\"ui-state-default\">Make</th>\n";
        $output .=   "<th class=\"ui-state-default\">Model</th>\n";
        $output .=   "<th class=\"ui-state-default\">Handling</th>\n";
        $output .=   "<th class=\"ui-state-default\">Interval</th>\n";
        $output .=   "<th class=\"ui-state-default\">Speed</th>\n";
        $output .=   "<th class=\"ui-state-default\">Accel</th>\n";
        $output .=   "<th class=\"ui-state-default\">Body</th>\n";
        $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
        $output .=   "<th class=\"ui-state-default\">Pilot</th>\n";
        $output .=   "<th class=\"ui-state-default\">Sensor</th>\n";
        $output .=   "<th class=\"ui-state-default\">Signature</th>\n";
        $output .=   "<th class=\"ui-state-default\">Autonav</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cargo</th>\n";
        $output .=   "<th class=\"ui-state-default\">Load</th>\n";
        $output .=   "<th class=\"ui-state-default\">Hardpoints</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firmpoints</th>\n";
        $output .=   "<th class=\"ui-state-default\">Seats</th>\n";
        $output .=   "<th class=\"ui-state-default\">Avail</th>\n";
        $output .=   "<th class=\"ui-state-default\">Street Index</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book</th>\n";
        $output .= "</tr>\n";

        switch ($vehicle) {
          case "aircraft":    $veh_name = "Aircraft";      break;
          case "groundcraft": $veh_name = "Groundcraft";   break;
          case "ldrones":     $veh_name = "Large Drones";  break;
          case "mdrones":     $veh_name = "Medium Drones"; break;
          case "odrones":     $veh_name = "Microdrone";    break;
          case "idrones":     $veh_name = "Minidrone";     break;
          case "sdrones":     $veh_name = "Small Drones";  break;
          case "watercraft":  $veh_name = "Watercraft";    break;
        }

        $nuyen = '&yen;';
        $q_string  = "select veh_id,veh_type,veh_make,veh_model,veh_onhand,veh_offhand,veh_interval,";
        $q_string .= "veh_onspeed,veh_offspeed,veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,veh_sensor,";
        $q_string .= "veh_sig,veh_nav,veh_cargo,veh_load,veh_hardpoints,veh_firmpoints,veh_onseats,veh_offseats,veh_avail,";
        $q_string .= "veh_perm,veh_basetime,veh_duration,veh_index,veh_cost,ver_book,veh_page ";
        $q_string .= "from vehicles ";
        $q_string .= "left join class on class.class_id = vehicles.veh_class ";
        $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
        $q_string .= "where class_name like \"" . $veh_name . "%\" and ver_admin = 1 ";
        $q_string .= "order by veh_make,veh_model,ver_version,veh_cost ";
        $q_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_vehicles) > 0) {
          while ($a_vehicles = mysql_fetch_array($q_vehicles)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.vehicles.fill.php?id="  . $a_vehicles['veh_id'] . "');jQuery('#dialogVehicle').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_vehicles('add.vehicles.del.php?id=" . $a_vehicles['veh_id'] . "');\">";
            $linkend = "</a>";

            $veh_handling = return_Handling($a_vehicles['veh_onhand'], $a_vehicles['veh_offhand']);

            $veh_speed = return_Speed($a_vehicles['veh_onspeed'], $a_vehicles['veh_offspeed']);

            $veh_acceleration = return_Acceleration($a_vehicles['veh_onacc'], $a_vehicles['veh_offacc']);

            $veh_seats = return_Seats($a_vehicles['veh_onseats'], $a_vehicles['veh_offseats']);

            $veh_avail = return_Avail($a_vehicles['veh_avail'], $a_vehicles['veh_perm'], $a_vehicles['veh_basetime'], $a_vehicles['veh_duration']);

            $veh_index = return_StreetIndex($a_vehicles['veh_index']);

            $veh_cost = return_Cost($a_vehicles['veh_cost']);

            $veh_book = return_Book($a_vehicles['ver_book'], $a_vehicles['veh_page']);

            $class = return_Class($a_vehicles['veh_perm']);

            $total = 0;
            $q_string  = "select r_veh_id ";
            $q_string .= "from r_vehicles ";
            $q_string .= "where r_veh_number = " . $a_vehicles['veh_id'] . " ";
            $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_vehicles) > 0) {
              while ($a_r_vehicles = mysql_fetch_array($q_r_vehicles)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_vehicles['veh_id']               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                              . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_vehicles['veh_type']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_vehicles['veh_make']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_vehicles['veh_model'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_handling                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_interval']         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_speed                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_acceleration                   . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_body']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_armor']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_pilot']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_sensor']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_sig']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_nav']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_cargo']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_load']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_hardpoints']       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_firmpoints']       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_seats                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_avail                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_index                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_cost                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $veh_book                           . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"25\">No Vehicles found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
        $output .= "<p class=\"ui-widget-class\">* On Road/Off Road Handling and Speed.</p>\n";

        print "document.getElementById('" . $vehicle . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }

      print "document.dialog.veh_type.value = '';\n";
      print "document.dialog.veh_make.value = '';\n";
      print "document.dialog.veh_model.value = '';\n";
      print "document.dialog.veh_onhand.value = '';\n";
      print "document.dialog.veh_offhand.value = '';\n";
      print "document.dialog.veh_interval.value = '';\n";
      print "document.dialog.veh_onspeed.value = '';\n";
      print "document.dialog.veh_offspeed.value = '';\n";
      print "document.dialog.veh_onacc.value = '';\n";
      print "document.dialog.veh_offacc.value = '';\n";
      print "document.dialog.veh_pilot.value = '';\n";
      print "document.dialog.veh_body.value = '';\n";
      print "document.dialog.veh_armor.value = '';\n";
      print "document.dialog.veh_sensor.value = '';\n";
      print "document.dialog.veh_sig.value = '';\n";
      print "document.dialog.veh_nav.value = '';\n";
      print "document.dialog.veh_cargo.value = '';\n";
      print "document.dialog.veh_load.value = '';\n";
      print "document.dialog.veh_hardpoints.value = '';\n";
      print "document.dialog.veh_firmpoints.value = '';\n";
      print "document.dialog.veh_onseats.value = '';\n";
      print "document.dialog.veh_offseats.value = '';\n";
      print "document.dialog.veh_avail.value = '';\n";
      print "document.dialog.veh_perm.value = '';\n";
      print "document.dialog.veh_basetime.value = '';\n";
      print "document.dialog.veh_duration.value = 0;\n";
      print "document.dialog.veh_index.value = '';\n";
      print "document.dialog.veh_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
