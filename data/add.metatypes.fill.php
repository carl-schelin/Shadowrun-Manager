<?php
# Script: add.metatypes.fill.php
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
    $package = "add.metatypes.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from metatypes");

      $q_string  = "select meta_name,meta_walk,meta_run,meta_swim,meta_notes,meta_book,meta_page ";
      $q_string .= "from metatypes ";
      $q_string .= "where meta_id = " . $formVars['id'];
      $q_metatypes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_metatypes = mysqli_fetch_array($q_metatypes);
      mysqli_free_result($q_metatypes);

      print "document.dialog.meta_name.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_name']) . "';\n";
      print "document.dialog.meta_walk.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_walk']) . "';\n";
      print "document.dialog.meta_run.value = '"  . mysqli_real_escape_string($db, $a_metatypes['meta_run'])  . "';\n";
      print "document.dialog.meta_swim.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_swim']) . "';\n";
      print "document.dialog.meta_notes.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_notes']) . "';\n";
      print "document.dialog.meta_book.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_book']) . "';\n";
      print "document.dialog.meta_page.value = '" . mysqli_real_escape_string($db, $a_metatypes['meta_page']) . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
