<?php
# Script: firearms.del.php
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
    $package = "firearms.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_firearms");

      $q_string  = "select r_fa_character ";
      $q_string .= "from r_firearms ";
      $q_string .= "where r_fa_id = " . $formVars['id'] . " ";
      $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_firearms = mysqli_fetch_array($q_r_firearms);

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character = " . $a_r_firearms['r_fa_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

# need to zero out parent ids for ammo.
      $q_string  = "update r_ammo ";
      $q_string .= "set r_ammo_parentid = 0 ";
      $q_string .= "where r_ammo_character = " . $a_r_firearms['r_fa_character'] . " and r_ammo_parentid = " . $formVars['id'] . " ";
      $result = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      $q_string  = "delete ";
      $q_string .= "from r_firearms ";
      $q_string .= "where r_fa_id= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Firearm deleted.');\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
