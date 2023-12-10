<?php
# Script: license.fill.php
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
    $package = "license.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_license");

      $q_string  = "select lic_type,lic_rating ";
      $q_string .= "from r_license ";
      $q_string .= "where lic_id = " . $formVars['id'];
      $q_r_license = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_license = mysqli_fetch_array($q_r_license);
      mysqli_free_result($q_r_license);

      print "document.edit.lic_type.value = '"   . mysqli_real_escape_string($db, $a_r_license['lic_type'])   . "';\n";
      print "document.edit.lic_rating.value = '" . mysqli_real_escape_string($db, $a_r_license['lic_rating']) . "';\n";

      print "document.edit.lic_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.lic_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
