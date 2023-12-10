<?php
# Script: street.fill.php
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
    $package = "street.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from street");

      $q_string  = "select st_cred,st_date,st_notes ";
      $q_string .= "from street ";
      $q_string .= "where st_id = " . $formVars['id'];
      $q_street = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_street = mysqli_fetch_array($q_street);
      mysqli_free_result($q_street);

      print "document.edit.st_cred.value = '"     . mysqli_real_escape_string($db, $a_street['st_cred'])     . "';\n";
      print "document.edit.st_date.value = '"     . mysqli_real_escape_string($db, $a_street['st_date'])     . "';\n";
      print "document.edit.st_notes.value = '"    . mysqli_real_escape_string($db, $a_street['st_notes'])    . "';\n";

      $value = (2000 - strlen($a_street['st_notes']));

      print "document.edit.st_noteLen.value = " . $value . ";\n";
      print "document.edit.st_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.st_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
