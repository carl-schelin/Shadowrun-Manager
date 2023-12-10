<?php
# Script: add.vehicles.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.vehicles.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from vehicles");

      $q_string  = "select veh_class,veh_type,veh_make,veh_model,veh_onhand,veh_offhand,veh_interval,veh_rate,veh_onspeed,veh_offspeed,";
      $q_string .= "veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,veh_sensor,veh_sig,veh_onseats,veh_offseats,veh_nav,veh_cargo,veh_load,";
      $q_string .= "veh_hardpoints,veh_firmpoints,veh_avail,veh_perm,veh_basetime,veh_duration,veh_index,veh_cost,veh_book,veh_page ";
      $q_string .= "from vehicles ";
      $q_string .= "where veh_id = " . $formVars['id'];
      $q_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_vehicles = mysqli_fetch_array($q_vehicles);
      mysqli_free_result($q_vehicles);

      print "document.dialog.veh_class.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_class'])        . "';\n";
      print "document.dialog.veh_type.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_type'])         . "';\n";
      print "document.dialog.veh_make.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_make'])         . "';\n";
      print "document.dialog.veh_model.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_model'])        . "';\n";
      print "document.dialog.veh_onhand.value = '"      . mysqli_real_escape_string($db, $a_vehicles['veh_onhand'])       . "';\n";
      print "document.dialog.veh_offhand.value = '"     . mysqli_real_escape_string($db, $a_vehicles['veh_offhand'])      . "';\n";
      print "document.dialog.veh_interval.value = '"    . mysqli_real_escape_string($db, $a_vehicles['veh_interval'])     . "';\n";
      print "document.dialog.veh_rate.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_rate'])         . "';\n";
      print "document.dialog.veh_onspeed.value = '"     . mysqli_real_escape_string($db, $a_vehicles['veh_onspeed'])      . "';\n";
      print "document.dialog.veh_offspeed.value = '"    . mysqli_real_escape_string($db, $a_vehicles['veh_offspeed'])     . "';\n";
      print "document.dialog.veh_onacc.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_onacc'])        . "';\n";
      print "document.dialog.veh_offacc.value = '"      . mysqli_real_escape_string($db, $a_vehicles['veh_offacc'])       . "';\n";
      print "document.dialog.veh_pilot.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_pilot'])        . "';\n";
      print "document.dialog.veh_body.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_body'])         . "';\n";
      print "document.dialog.veh_armor.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_armor'])        . "';\n";
      print "document.dialog.veh_sensor.value = '"      . mysqli_real_escape_string($db, $a_vehicles['veh_sensor'])       . "';\n";
      print "document.dialog.veh_sig.value = '"         . mysqli_real_escape_string($db, $a_vehicles['veh_sig'])          . "';\n";
      print "document.dialog.veh_nav.value = '"         . mysqli_real_escape_string($db, $a_vehicles['veh_nav'])          . "';\n";
      print "document.dialog.veh_cargo.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_cargo'])        . "';\n";
      print "document.dialog.veh_load.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_load'])         . "';\n";
      print "document.dialog.veh_hardpoints.value = '"  . mysqli_real_escape_string($db, $a_vehicles['veh_hardpoints'])   . "';\n";
      print "document.dialog.veh_firmpoints.value = '"  . mysqli_real_escape_string($db, $a_vehicles['veh_firmpoints'])   . "';\n";
      print "document.dialog.veh_onseats.value = '"     . mysqli_real_escape_string($db, $a_vehicles['veh_onseats'])      . "';\n";
      print "document.dialog.veh_offseats.value = '"    . mysqli_real_escape_string($db, $a_vehicles['veh_offseats'])     . "';\n";
      print "document.dialog.veh_avail.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_avail'])        . "';\n";
      print "document.dialog.veh_perm.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_perm'])         . "';\n";
      print "document.dialog.veh_basetime.value = '"    . mysqli_real_escape_string($db, $a_vehicles['veh_basetime'])     . "';\n";
      print "document.dialog.veh_duration.value = '"    . mysqli_real_escape_string($db, $a_vehicles['veh_duration'])     . "';\n";
      print "document.dialog.veh_index.value = '"       . mysqli_real_escape_string($db, $a_vehicles['veh_index'])        . "';\n";
      print "document.dialog.veh_cost.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_cost'])         . "';\n";
      print "document.dialog.veh_book.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_book'])         . "';\n";
      print "document.dialog.veh_page.value = '"        . mysqli_real_escape_string($db, $a_vehicles['veh_page'])         . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
