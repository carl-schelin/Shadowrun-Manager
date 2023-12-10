<?php
# Script: add.adept.fill.php
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
    $package = "add.adept.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from adept");

      $q_string  = "select adp_name,adp_desc,adp_power,adp_active,adp_level,adp_book,adp_page ";
      $q_string .= "from adept ";
      $q_string .= "where adp_id = " . $formVars['id'];
      $q_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_adept = mysqli_fetch_array($q_adept);
      mysqli_free_result($q_adept);

      print "document.dialog.adp_name.value = '"        . mysqli_real_escape_string($db, $a_adept['adp_name'])       . "';\n";
      print "document.dialog.adp_desc.value = '"        . mysqli_real_escape_string($db, $a_adept['adp_desc'])       . "';\n";
      print "document.dialog.adp_power.value = '"       . mysqli_real_escape_string($db, $a_adept['adp_power'])      . "';\n";
      print "document.dialog.adp_active.value = '"      . mysqli_real_escape_string($db, $a_adept['adp_active'])     . "';\n";
      print "document.dialog.adp_level.value = '"       . mysqli_real_escape_string($db, $a_adept['adp_level'])      . "';\n";
      print "document.dialog.adp_book.value = '"        . mysqli_real_escape_string($db, $a_adept['adp_book'])       . "';\n";
      print "document.dialog.adp_page.value = '"        . mysqli_real_escape_string($db, $a_adept['adp_page'])       . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";
#      print "document.dialog.update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
