<?php
# Script: gear.fill.php
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
    $package = "gear.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_gear");

      $q_string  = "select gear_name,gear_rating,gear_capacity,r_gear_amount,r_gear_details,r_gear_number ";
      $q_string .= "from r_gear ";
      $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
      $q_string .= "where r_gear_id = " . $formVars['id'];
      $q_r_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_gear = mysqli_fetch_array($q_r_gear);
      mysqli_free_result($q_r_gear);

      $rating = return_Rating($a_r_gear['gear_rating']);

      $capacity = return_Capacity($a_r_gear['gear_capacity']);

      print "document.getElementById('r_gear_item').innerHTML = '" . mysqli_real_escape_string($db, $a_r_gear['gear_name'])      . " [Rating: " . $rating . ", Capacity: " . $capacity . "]';\n\n";
      print "document.edit.r_gear_number.value = '"                . mysqli_real_escape_string($db, $a_r_gear['r_gear_number'])  . "';\n\n";
      print "document.edit.r_gear_amount.value = '"                . mysqli_real_escape_string($db, $a_r_gear['r_gear_amount'])  . "';\n\n";
      print "document.edit.r_gear_details.value = '"               . mysqli_real_escape_string($db, $a_r_gear['r_gear_details']) . "';\n\n";

      print "document.edit.r_gear_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_gear_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
