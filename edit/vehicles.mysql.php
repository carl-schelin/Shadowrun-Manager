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

  if (isset($_SESSION['username'])) {
    $package = "vehicles.mysql.php";
    $formVars['update']             = clean($_GET['update'],              10);
    $formVars['r_veh_character']     = clean($_GET['r_veh_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_veh_character'] == '') {
      $formVars['r_veh_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_veh_number']      = clean($_GET['r_veh_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_veh_number'] == '') {
          $formVars['r_veh_number'] = 1;
        }

        if ($formVars['r_veh_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_veh_character   =   " . $formVars['r_veh_character']   . "," .
            "r_veh_number      =   " . $formVars['r_veh_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_vehicles set r_veh_id = NULL," . $q_string;
            $message = "Vehicle added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_veh_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_veh_refresh\" value=\"Refresh My Vehicle Listing\" onClick=\"javascript:attach_vehicles('vehicles.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_veh_update\"  value=\"Update Vehicle\"          onClick=\"javascript:attach_vehicles('vehicles.mysql.php', 1);hideDiv('vehicles-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_veh_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_veh_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Vehicle Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Vehicle: <span id=\"r_veh_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('vehicles_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $vehicle_list = array("drone", "groundcraft", "watercraft", "aircraft");

        foreach ($vehicle_list as &$vehicles) {

          $output  = "<p></p>\n";
          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .= "  <th class=\"ui-state-default\">My Vehicles</th>\n";
          $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $vehicles . "-listing-help');\">Help</a></th>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

          $output .= "<div id=\"" . $vehicles . "-listing-help\" style=\"display: none\">\n";

          $output .= "<div class=\"main-help ui-widget-content\">\n";

          $output .= "<ul>\n";
          $output .= "  <li><strong>Weapon Listing</strong>\n";
          $output .= "  <ul>\n";
          $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
          $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
          $output .= "  </ul></li>\n";
          $output .= "</ul>\n";

          $output .= "<ul>\n";
          $output .= "  <li><strong>Notes</strong>\n";
          $output .= "  <ul>\n";
          $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
          $output .= "  </ul></li>\n";
          $output .= "</ul>\n";

          $output .= "</div>\n";

          $output .= "</div>\n";

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Type</th>\n";
          $output .=   "<th class=\"ui-state-default\">Vehicle</th>\n";
          $output .=   "<th class=\"ui-state-default\">Handling</th>\n";
          $output .=   "<th class=\"ui-state-default\">Speed</th>\n";
          $output .=   "<th class=\"ui-state-default\">Acceleration</th>\n";
          $output .=   "<th class=\"ui-state-default\">Body</th>\n";
          $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
          $output .=   "<th class=\"ui-state-default\">Pilot</th>\n";
          $output .=   "<th class=\"ui-state-default\">Sensor</th>\n";
          $output .=   "<th class=\"ui-state-default\">Seats</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $q_string  = "select veh_id,class_name,veh_class,veh_type,veh_make,veh_model,veh_onhand,veh_offhand,";
          $q_string .= "veh_onspeed,veh_offspeed,veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,veh_sensor,";
          $q_string .= "veh_onseats,veh_offseats,veh_avail,veh_perm,veh_cost,ver_book,veh_page ";
          $q_string .= "from vehicles ";
          $q_string .= "left join class on class.class_id = vehicles.veh_class ";
          $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
          $q_string .= "where class_name like \"%" . $vehicles . "%\" and ver_active = 1 ";
          $q_string .= "order by veh_make,veh_model ";
          $q_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_vehicles) > 0) {
            while ($a_vehicles = mysql_fetch_array($q_vehicles)) {

# this adds the veh_id to the r_veh_character
              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('vehicles.mysql.php?update=0&r_veh_character=" . $formVars['r_veh_character'] . "&r_veh_number=" . $a_vehicles['veh_id'] . "');\">";
              $linkend = "</a>";

              $veh_handling = return_Handling($a_vehicles['veh_onhand'], $a_vehicles['veh_offhand']);

              $veh_speed = return_Speed($a_vehicles['veh_onspeed'], $a_vehicles['veh_offspeed']);

              $veh_acceleration = return_Acceleration($a_vehicles['veh_onacc'], $a_vehicles['veh_offacc']);

              $veh_seats = return_Seats($a_vehicles['veh_onseats'], $a_vehicles['veh_offseats']);

              $veh_avail = return_Avail($a_vehicles['veh_avail'], $a_vehicles['veh_perm']);

              $veh_cost = return_Cost($a_vehicles['veh_cost']);

              $veh_book = return_Book($a_vehicles['ver_book'], $a_vehicles['veh_page']);

              $class = "ui-widget-content";
              if ($a_vehicles['veh_perm'] == 'R') {
                $class = "ui-state-highlight";
              }
              if ($a_vehicles['veh_perm'] == 'F') {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"                     . $a_vehicles['veh_type']             . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_vehicles['veh_make']  . " " . $a_vehicles['veh_model'] . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_handling                       . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_speed                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_acceleration                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_pilot']            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_body']             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_armor']            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_vehicles['veh_sensor']           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_seats                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_cost                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $veh_book                           . "</td>\n";
              $output .= "</tr>\n";
            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">No Vehicles found.</td>\n";
            $output .= "</tr>\n";
          }

          $output .= "</table>\n";

          print "document.getElementById('" . $vehicles . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
        }

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Vehicles</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('my-vehicles-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"my-vehicles-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Vehicle</th>\n";
      $output .=   "<th class=\"ui-state-default\">Handling</th>\n";
      $output .=   "<th class=\"ui-state-default\">Speed</th>\n";
      $output .=   "<th class=\"ui-state-default\">Acceleration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Body</th>\n";
      $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
      $output .=   "<th class=\"ui-state-default\">Pilot</th>\n";
      $output .=   "<th class=\"ui-state-default\">Sensor</th>\n";
      $output .=   "<th class=\"ui-state-default\">Seats</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select veh_id,r_veh_id,class_name,veh_class,veh_type,veh_make,veh_model,veh_onhand,";
      $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,veh_sensor,";
      $q_string .= "veh_onseats,veh_offseats,veh_avail,veh_perm,veh_cost,ver_book,veh_page ";
      $q_string .= "from r_vehicles ";
      $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
      $q_string .= "left join class on class.class_id = vehicles.veh_class ";
      $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
      $q_string .= "where r_veh_character = " . $formVars['r_veh_character'] . " ";
      $q_string .= "order by veh_make,veh_model ";
      $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_vehicles) > 0) {
        while ($a_r_vehicles = mysql_fetch_array($q_r_vehicles)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_vehacc(" . $a_r_vehicles['r_veh_id'] . ");showDiv('vehicles-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_vehicles('vehicles.del.php?id="  . $a_r_vehicles['r_veh_id'] . "');\">";
          $linkend   = "</a>";

          $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

          $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

          $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

          $veh_seats = return_Seats($a_r_vehicles['veh_onseats'], $a_r_vehicles['veh_offseats']);

          $costtotal += $a_r_vehicles['veh_cost'];

          $veh_avail = return_Avail($a_r_vehicles['veh_avail'], $a_r_vehicles['veh_perm']);

          $veh_cost = return_Cost($a_r_vehicles['veh_cost']);

          $veh_book = return_Book($a_r_vehicles['ver_book'], $a_r_vehicles['veh_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_veh_number']) && $formVars['r_veh_number'] == $a_r_vehicles['veh_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                   . $a_r_vehicles['class_name']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                   . $a_r_vehicles['veh_type']               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                   . $linkstart . $a_r_vehicles['veh_make']  . " " . $a_r_vehicles['veh_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_handling                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_speed                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_acceleration                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $a_r_vehicles['veh_body']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $a_r_vehicles['veh_armor']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $a_r_vehicles['veh_pilot']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $a_r_vehicles['veh_sensor']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_seats                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_avail                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_cost                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"            . $veh_book                               . "</td>\n";
          $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_veh_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Vehicles\" and r_acc_character = " . $formVars['r_veh_character'] . " and r_acc_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_fireacc('fireacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";

              $acc_name = $a_r_accessory['acc_name'];
              if ($a_r_accessory['acc_mount'] != '') {
                $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
              }

              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name   . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book             . "</td>\n";
              $output .= "</tr>\n";
            }
          }


# display the autosofts
          $q_string  = "select r_gear_id,gear_id,gear_name,gear_avail,gear_perm,ver_book,gear_page ";
          $q_string .= "from r_gear ";
          $q_string .= "left join gear     on gear.gear_id    = r_gear.r_gear_number ";
          $q_string .= "left join class    on class.class_id  = gear.gear_class ";
          $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
          $q_string .= "where r_gear_character = " . $formVars['r_veh_character'] . " and r_gear_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
          $q_string .= "order by gear_name,r_gear_details,gear_rating,gear_class ";
          $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_gear) > 0) {
            while ($a_r_gear = mysql_fetch_array($q_r_gear)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_vehauto('vehauto.del.php?id="  . $a_r_gear['r_gear_id'] . "');\">";

              $gear_avail = return_Avail($a_r_gear['gear_avail'], $a_r_gear['gear_perm']);

              $gear_book = return_Book($a_r_gear['ver_book'], $a_r_gear['gear_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel              . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_gear['gear_name']   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $gear_avail                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $gear_book                         . "</td>\n";
              $output .= "</tr>\n";


              $q_string  = "select acc_name,acc_avail,acc_perm,ver_book,acc_page ";
              $q_string .= "from r_accessory ";
              $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
              $q_string .= "left join versions  on versions.ver_id  = accessory.acc_book ";
              $q_string .= "where r_acc_character = " . $formVars['r_veh_character'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
              $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              if (mysql_num_rows($q_r_accessory) > 0) {
                while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

                  $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

                  $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

                  $output .= "<tr>\n";
                  $output .= "  <td class=\"" . $class . "\">"        .                        " &nbsp; "   . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . "\">" . "&gt;&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                          . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "--"                                . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                           . "</td>\n";
                  $output .= "</tr>\n";

                }
              }
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
          $q_string .= "where r_fa_character = " . $formVars['r_veh_character'] . " and r_fa_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
          $q_string .= "order by fa_name,fa_class ";
          $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_firearms) > 0) {
            while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_vehfire('vehfire.del.php?id="  . $a_r_firearms['r_fa_id'] . "');\">";

              $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

              $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_fa_number']) && $formVars['r_fa_number'] == $a_r_firearms['fa_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel              . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_firearms['fa_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                           . "</td>\n";
              $output .= "</tr>\n";



# associate any ammo with the weapon
              $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
              $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
              $q_string .= "from r_ammo ";
              $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
              $q_string .= "left join class on class.class_id = ammo.ammo_class ";
              $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
              $q_string .= "where r_ammo_character = " . $formVars['r_veh_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
              $q_string .= "order by ammo_name,class_name ";
              $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              if (mysql_num_rows($q_r_ammo) > 0) {
                while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

                  $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

                  $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

                  $class = "ui-widget-content";
                  if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
                    $class = "ui-state-error";
                  }

                  $output .= "<tr>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . "\">"        . "&gt;&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                         . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                          . "</td>\n";
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
          $q_string .= "where r_ammo_character = " . $formVars['r_veh_character'] . " and r_ammo_parentveh = " . $a_r_vehicles['r_veh_id'] . " ";
          $q_string .= "order by ammo_name,class_name ";
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_vehammo('vehammo.del.php?id="  . $a_r_ammo['r_ammo_id'] . "');\">";

              $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

              $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_ammo['class_name']                                             . "</td>\n";
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
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                         . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                          . "</td>\n";
              $output .= "</tr>\n";

            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">No Vehicles added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_vehicles);

      print "document.getElementById('my_vehicles_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
