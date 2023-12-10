<?php
# Script: add.ic.fill.php
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
    $package = "add.ic.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from ic");

      $q_string  = "select ic_name,ic_defense,ic_description,ic_book,ic_page ";
      $q_string .= "from ic ";
      $q_string .= "where ic_id = " . $formVars['id'];
      $q_ic = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_ic = mysqli_fetch_array($q_ic);
      mysqli_free_result($q_ic);

      print "document.dialog.ic_name.value = '"        . mysqli_real_escape_string($db, $a_ic['ic_name'])        . "';\n";
      print "document.dialog.ic_defense.value = '"     . mysqli_real_escape_string($db, $a_ic['ic_defense'])     . "';\n";
      print "document.dialog.ic_description.value = '" . mysqli_real_escape_string($db, $a_ic['ic_description']) . "';\n";
      print "document.dialog.ic_book.value = '"        . mysqli_real_escape_string($db, $a_ic['ic_book'])        . "';\n";
      print "document.dialog.ic_page.value = '"        . mysqli_real_escape_string($db, $a_ic['ic_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
