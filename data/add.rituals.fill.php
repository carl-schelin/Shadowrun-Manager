<?php
# Script: add.rituals.fill.php
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
    $package = "add.rituals.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from rituals");

      $q_string  = "select rit_name,rit_anchor,rit_link,rit_minion,rit_spell,rit_spotter,rit_threshold,rit_length,rit_duration,rit_book,rit_page ";
      $q_string .= "from rituals ";
      $q_string .= "where rit_id = " . $formVars['id'];
      $q_rituals = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_rituals = mysqli_fetch_array($q_rituals);
      mysqli_free_result($q_rituals);

      print "document.dialog.rit_name.value = '"       . mysqli_real_escape_string($db, $a_rituals['rit_name'])      . "';\n";
      print "document.dialog.rit_threshold.value = '"  . mysqli_real_escape_string($db, $a_rituals['rit_threshold']) . "';\n";
      print "document.dialog.rit_length.value = '"     . mysqli_real_escape_string($db, $a_rituals['rit_length'])    . "';\n";
      print "document.dialog.rit_duration.value = '"   . mysqli_real_escape_string($db, $a_rituals['rit_duration'])  . "';\n";
      print "document.dialog.rit_book.value = '"       . mysqli_real_escape_string($db, $a_rituals['rit_book'])      . "';\n";
      print "document.dialog.rit_page.value = '"       . mysqli_real_escape_string($db, $a_rituals['rit_page'])      . "';\n";

      if ($a_rituals['rit_anchor'] == 1) {
        print "document.dialog.rit_anchor.checked = true;\n";
      } else {
        print "document.dialog.rit_anchor.checked = false;\n";
      }
      if ($a_rituals['rit_minion'] == 1) {
        print "document.dialog.rit_minion.checked = true;\n";
      } else {
        print "document.dialog.rit_minion.checked = false;\n";
      }
      if ($a_rituals['rit_link'] == 1) {
        print "document.dialog.rit_link.checked = true;\n";
      } else {
        print "document.dialog.rit_link.checked = false;\n";
      }
      if ($a_rituals['rit_spell'] == 1) {
        print "document.dialog.rit_spell.checked = true;\n";
      } else {
        print "document.dialog.rit_spell.checked = false;\n";
      }
      if ($a_rituals['rit_spotter'] == 1) {
        print "document.dialog.rit_spotter.checked = true;\n";
      } else {
        print "document.dialog.rit_spotter.checked = false;\n";
      }


      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
