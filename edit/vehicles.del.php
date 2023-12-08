<?php
# Script: vehicles.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Delete association entries

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "vehicles.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_vehicles");

      $q_string  = "select r_veh_character ";
      $q_string .= "from r_vehicles ";
      $q_string .= "where r_veh_id = " . $formVars['id'] . " ";
      $q_r_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_vehicles = mysqli_fetch_array($q_r_vehicles);

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character = " . $a_r_vehicles['r_veh_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

# need to zero out parent ids for firearms and ammo. This would be grenades mainly
      $q_string  = "update r_ammo ";
      $q_string .= "set r_ammo_parentveh = 0 ";
      $q_string .= "where r_ammo_character = " . $a_r_vehicles['r_veh_character'] . " and r_ammo_parentveh = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      $q_string  = "update r_firearms ";
      $q_string .= "set r_fa_parentid = 0 ";
      $q_string .= "where r_fa_character = " . $a_r_vehicles['r_veh_character'] . " and r_fa_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      $q_string  = "delete ";
      $q_string .= "from r_vehicles ";
      $q_string .= "where r_veh_id= " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Vehicle deleted.');\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
