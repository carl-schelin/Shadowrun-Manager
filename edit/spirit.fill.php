<?php
# Script: spirit.fill.php
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
    $package = "spirit.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_spirit");

      $q_string  = "select spirit_name,r_spirit_number,r_spirit_force,r_spirit_services,r_spirit_bound ";
      $q_string .= "from r_spirit ";
      $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
      $q_string .= "where r_spirit_id = " . $formVars['id'];
      $q_r_spirit = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_spirit = mysqli_fetch_array($q_r_spirit);
      mysql_free_result($q_r_spirit);

      print "document.getElementById('r_spirit_item').innerHTML = '" . mysql_real_escape_string($a_r_spirit['spirit_name']) . "';\n\n";

      print "document.edit.r_spirit_number.value = '"   . mysql_real_escape_string($a_r_spirit['r_spirit_number'])   . "';\n";
      print "document.edit.r_spirit_force.value = '"    . mysql_real_escape_string($a_r_spirit['r_spirit_force'])    . "';\n";
      print "document.edit.r_spirit_services.value = '" . mysql_real_escape_string($a_r_spirit['r_spirit_services']) . "';\n";

      if ($a_r_spirit['r_spirit_bound']) {
        print "document.edit.r_spirit_bound.checked = true;\n";
      } else {
        print "document.edit.r_spirit_bound.checked = false;\n";
      }

      print "document.edit.r_spirit_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.spirit_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
