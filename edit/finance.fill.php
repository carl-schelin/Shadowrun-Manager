<?php
# Script: finance.fill.php
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
    $package = "finance.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from finance");

      $q_string  = "select fin_funds,fin_date,fin_notes ";
      $q_string .= "from finance ";
      $q_string .= "where fin_id = " . $formVars['id'];
      $q_finance = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_finance = mysqli_fetch_array($q_finance);
      mysqli_free_result($q_finance);

      print "document.edit.fin_funds.value = '"    . mysqli_real_escape_string($db, $a_finance['fin_funds'])    . "';\n";
      print "document.edit.fin_date.value = '"     . mysqli_real_escape_string($db, $a_finance['fin_date'])     . "';\n";
      print "document.edit.fin_notes.value = '"    . mysqli_real_escape_string($db, $a_finance['fin_notes'])    . "';\n";

      $value = (2000 - strlen($a_finance['fin_notes']));

      print "document.edit.fin_noteLen.value = " . $value . ";\n";
      print "document.edit.fin_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.fin_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
