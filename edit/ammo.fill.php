<?php
# Script: ammo.fill.php
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
    $package = "ammo.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_ammo");

      $q_string  = "select r_ammo_number,r_ammo_rounds,ammo_name,ammo_rounds ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "where r_ammo_id = " . $formVars['id'];
      $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_ammo = mysqli_fetch_array($q_r_ammo);
      mysql_free_result($q_r_ammo);

      if ($a_r_ammo['ammo_rounds'] > 1) {
        print "document.getElementById('number_of_rounds').innerHTML = '" . mysql_real_escape_string(" Boxes with " . $a_r_ammo['ammo_rounds'] . " rounds per box.")     . "';\n\n";
      } else {
        print "document.getElementById('number_of_rounds').innerHTML = '" . mysql_real_escape_string(" individual rounds.")     . "';\n\n";
      }

      print "document.getElementById('r_ammo_item').innerHTML = '" . mysql_real_escape_string($a_r_ammo['ammo_name'])     . "';\n\n";
      print "document.edit.r_ammo_number.value = '"                . mysql_real_escape_string($a_r_ammo['r_ammo_number']) . "';\n";
      print "document.edit.r_ammo_rounds.value = '"                . mysql_real_escape_string($a_r_ammo['r_ammo_rounds']) . "';\n";

      print "document.edit.r_ammo_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_ammo_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
