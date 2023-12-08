<?php
# Script: adept.fill.php
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
    $package = "adept.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_adept");

      $q_string  = "select r_adp_number,r_adp_level,r_adp_specialize,adp_name ";
      $q_string .= "from r_adept ";
      $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
      $q_string .= "where r_adp_id = " . $formVars['id'];
      $q_r_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_adept = mysqli_fetch_array($q_r_adept);
      mysql_free_result($q_r_adept);

      print "document.getElementById('r_adp_item').innerHTML = '" . mysql_real_escape_string($a_r_adept['adp_name']) . "';\n\n";

      print "document.edit.r_adp_number.value = '"     . mysql_real_escape_string($a_r_adept['r_adp_number'])     . "';\n";
      print "document.edit.r_adp_level.value = '"      . mysql_real_escape_string($a_r_adept['r_adp_level'])      . "';\n";
      print "document.edit.r_adp_specialize.value = '" . mysql_real_escape_string($a_r_adept['r_adp_specialize']) . "';\n";

      print "document.edit.r_adp_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_adp_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
