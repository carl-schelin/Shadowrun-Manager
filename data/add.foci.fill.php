<?php
# Script: add.foci.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.foci.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from foci");

      $q_string  = "select foci_name,foci_karma,foci_avail,foci_perm,foci_cost,foci_book,foci_page ";
      $q_string .= "from foci ";
      $q_string .= "where foci_id = " . $formVars['id'];
      $q_foci = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_foci = mysqli_fetch_array($q_foci);
      mysqli_free_result($q_foci);

      print "document.dialog.foci_name.value = '"       . mysqli_real_escape_string($db, $a_foci['foci_name'])    . "';\n";
      print "document.dialog.foci_karma.value = '"      . mysqli_real_escape_string($db, $a_foci['foci_karma'])   . "';\n";
      print "document.dialog.foci_avail.value = '"      . mysqli_real_escape_string($db, $a_foci['foci_avail'])   . "';\n";
      print "document.dialog.foci_perm.value = '"       . mysqli_real_escape_string($db, $a_foci['foci_perm'])    . "';\n";
      print "document.dialog.foci_cost.value = '"       . mysqli_real_escape_string($db, $a_foci['foci_cost'])    . "';\n";
      print "document.dialog.foci_book.value = '"       . mysqli_real_escape_string($db, $a_foci['foci_book'])    . "';\n";
      print "document.dialog.foci_page.value = '"       . mysqli_real_escape_string($db, $a_foci['foci_page'])    . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
