<?php
# Script: armor.fill.php
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
    $package = "armor.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_armor");

      $q_string  = "select r_arm_details,r_arm_number,arm_name,arm_rating ";
      $q_string .= "from r_armor ";
      $q_string .= "left join armor on armor.arm_id = r_armor.r_arm_number ";
      $q_string .= "where r_arm_id = " . $formVars['id'];
      $q_r_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_armor = mysqli_fetch_array($q_r_armor);
      mysql_free_result($q_r_armor);

      $rating = return_Rating($a_r_armor['arm_rating']);

      $arm_name = $a_r_armor['arm_name'] . " [Rating: " . $rating . "]";

      print "document.getElementById('r_arm_item').innerHTML = '" . mysql_real_escape_string($arm_name)                      . "';\n\n";
      print "document.edit.r_arm_details.value = '"               . mysql_real_escape_string($a_r_armor['r_arm_details'])    . "';\n";
      print "document.edit.r_arm_number.value = '"                . mysql_real_escape_string($a_r_armor['r_arm_number'])     . "';\n";

      print "document.edit.r_arm_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_arm_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
