<?php
# Script: powers.fill.php
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
    $package = "powers.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from spirits");

      $q_string  = "select spirit_name ";
      $q_string .= "from spirits ";
      $q_string .= "where spirit_id = " . $formVars['id'];
      $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_spirits = mysqli_fetch_array($q_spirits);
      mysql_free_result($q_spirits);

      print "document.getElementById('r_spirit_item').innerHTML = '" . mysql_real_escape_string($a_spirits['spirit_name']) . "';\n\n";

      print "document.spirits.r_spirit_id.value = " . $formVars['id'] . ";\n";
      print "document.spirits.r_spirit_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
