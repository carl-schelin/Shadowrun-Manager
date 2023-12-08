<?php
# Script: vehicles.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "vehicles.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_vehicles");

      $q_string  = "select class_name,veh_make,veh_model,veh_onhand,veh_offhand,veh_onspeed,veh_offspeed,";
      $q_string .= "veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,veh_sensor ";
      $q_string .= "from r_vehicles ";
      $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
      $q_string .= "left join class on class.class_id = vehicles.veh_class ";
      $q_string .= "where r_veh_id = " . $formVars['id'];
      $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_vehicles = mysqli_fetch_array($q_r_vehicles);
      mysql_free_result($q_r_vehicles);

      $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

      $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

      $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

      $vehicle = " [" . $a_r_vehicles['class_name'] . ", Handling " . $veh_handling . ", Speed " . $veh_speed . ", Accel  " . $veh_acceleration . ", Body " . $a_r_vehicles['veh_body'] . ", Armor " . $a_r_vehicles['veh_armor'] . ", Pilot " . $a_r_vehicles['veh_pilot'] . ", Sensor " . $a_r_vehicles['veh_sensor'] . "]";

      print "document.getElementById('r_veh_item').innerHTML = '" . mysql_real_escape_string($a_r_vehicles['veh_make'] . " " . $a_r_vehicles['veh_model'] . $vehicle) . "';\n\n";
      print "document.edit.r_veh_number.value = '"                . mysql_real_escape_string($a_r_vehicles['r_veh_number']) . "';\n\n";

      print "document.edit.r_veh_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_veh_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
