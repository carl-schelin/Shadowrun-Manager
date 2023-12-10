<?php
# Script: powers.dialog.php
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
    $package = "powers.dialog.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from sp_powers");

      $q_string  = "select sp_power_specialize ";
      $q_string .= "from sp_powers ";
      $q_string .= "where sp_power_id = " . $formVars['id'];
      $q_sp_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_sp_powers = mysqli_fetch_array($q_sp_powers);
      mysqli_free_result($q_sp_powers);

      print "document.power.sp_power_specialize.value = '" . mysqli_real_escape_string($db, $a_sp_powers['sp_power_specialize']) . "';\n";

      print "document.power.id.value = " . $formVars['id'] . ";\n";
      print "$(\"#power-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
