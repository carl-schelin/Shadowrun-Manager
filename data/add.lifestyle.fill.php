<?php
# Script: add.lifestyle.fill.php
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
    $package = "add.lifestyle.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from lifestyle");

      $q_string  = "select life_style,life_mincost,life_maxcost,life_book,life_page ";
      $q_string .= "from lifestyle ";
      $q_string .= "where life_id = " . $formVars['id'];
      $q_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_lifestyle = mysqli_fetch_array($q_lifestyle);
      mysqli_free_result($q_lifestyle);

      print "document.dialog.life_style.value = '"           . mysqli_real_escape_string($db, $a_lifestyle['life_style'])             . "';\n";
      print "document.dialog.life_mincost.value = '"         . mysqli_real_escape_string($db, $a_lifestyle['life_mincost'])           . "';\n";
      print "document.dialog.life_maxcost.value = '"         . mysqli_real_escape_string($db, $a_lifestyle['life_maxcost'])           . "';\n";
      print "document.dialog.life_book.value = '"            . mysqli_real_escape_string($db, $a_lifestyle['life_book'])              . "';\n";
      print "document.dialog.life_page.value = '"            . mysqli_real_escape_string($db, $a_lifestyle['life_page'])              . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
