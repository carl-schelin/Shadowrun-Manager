<?php
# Script: add.tradition.fill.php
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
    $package = "add.tradition.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from tradition");

      $q_string  = "select trad_name,trad_description,trad_combat,trad_detection,trad_health,";
      $q_string .= "trad_illusion,trad_manipulation,trad_drainleft,trad_drainright,trad_book,trad_page ";
      $q_string .= "from tradition ";
      $q_string .= "where trad_id = " . $formVars['id'];
      $q_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_tradition = mysqli_fetch_array($q_tradition);
      mysqli_free_result($q_tradition);

      print "document.dialog.trad_name.value = '"          . mysqli_real_escape_string($db, $a_tradition['trad_name'])          . "';\n";
      print "document.dialog.trad_description.value = '"   . mysqli_real_escape_string($db, $a_tradition['trad_description'])   . "';\n";
      print "document.dialog.trad_combat.value = '"        . mysqli_real_escape_string($db, $a_tradition['trad_combat'])        . "';\n";
      print "document.dialog.trad_detection.value = '"     . mysqli_real_escape_string($db, $a_tradition['trad_detection'])     . "';\n";
      print "document.dialog.trad_health.value = '"        . mysqli_real_escape_string($db, $a_tradition['trad_health'])        . "';\n";
      print "document.dialog.trad_illusion.value = '"      . mysqli_real_escape_string($db, $a_tradition['trad_illusion'])      . "';\n";
      print "document.dialog.trad_manipulation.value = '"  . mysqli_real_escape_string($db, $a_tradition['trad_manipulation'])  . "';\n";
      print "document.dialog.trad_drainleft.value = '"     . mysqli_real_escape_string($db, $a_tradition['trad_drainleft'])     . "';\n";
      print "document.dialog.trad_drainright.value = '"    . mysqli_real_escape_string($db, $a_tradition['trad_drainright'])    . "';\n";
      print "document.dialog.trad_book.value = '"          . mysqli_real_escape_string($db, $a_tradition['trad_book'])          . "';\n";
      print "document.dialog.trad_page.value = '"          . mysqli_real_escape_string($db, $a_tradition['trad_page'])          . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
