<?php
# Script: add.cyberdeck.fill.php
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
    $package = "add.cyberdeck.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberdeck");

      $q_string  = "select deck_brand,deck_model,deck_rating,deck_attack,deck_sleaze,deck_data,deck_firewall,";
      $q_string .= "deck_programs,deck_access,deck_avail,deck_perm,deck_cost,deck_book,deck_page,deck_index,";
      $q_string .= "deck_persona,deck_hardening,deck_memory,deck_storage,deck_load,deck_io,deck_basetime,deck_duration,";
      $q_string .= "deck_response ";
      $q_string .= "from cyberdeck ";
      $q_string .= "where deck_id = " . $formVars['id'];
      $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_cyberdeck = mysqli_fetch_array($q_cyberdeck);
      mysqli_free_result($q_cyberdeck);

      print "document.dialog.deck_brand.value = '"     . mysqli_real_escape_string($db, $a_cyberdeck['deck_brand'])     . "';\n";
      print "document.dialog.deck_model.value = '"     . mysqli_real_escape_string($db, $a_cyberdeck['deck_model'])     . "';\n";
      print "document.dialog.deck_rating.value = '"    . mysqli_real_escape_string($db, $a_cyberdeck['deck_rating'])    . "';\n";
      print "document.dialog.deck_attack.value = '"    . mysqli_real_escape_string($db, $a_cyberdeck['deck_attack'])    . "';\n";
      print "document.dialog.deck_sleaze.value = '"    . mysqli_real_escape_string($db, $a_cyberdeck['deck_sleaze'])    . "';\n";
      print "document.dialog.deck_data.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_data'])      . "';\n";
      print "document.dialog.deck_firewall.value = '"  . mysqli_real_escape_string($db, $a_cyberdeck['deck_firewall'])  . "';\n";
      print "document.dialog.deck_persona.value = '"   . mysqli_real_escape_string($db, $a_cyberdeck['deck_persona'])   . "';\n";
      print "document.dialog.deck_hardening.value = '" . mysqli_real_escape_string($db, $a_cyberdeck['deck_hardening']) . "';\n";
      print "document.dialog.deck_memory.value = '"    . mysqli_real_escape_string($db, $a_cyberdeck['deck_memory'])    . "';\n";
      print "document.dialog.deck_storage.value = '"   . mysqli_real_escape_string($db, $a_cyberdeck['deck_storage'])   . "';\n";
      print "document.dialog.deck_load.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_load'])      . "';\n";
      print "document.dialog.deck_io.value = '"        . mysqli_real_escape_string($db, $a_cyberdeck['deck_io'])        . "';\n";
      print "document.dialog.deck_response.value = '"  . mysqli_real_escape_string($db, $a_cyberdeck['deck_response'])  . "';\n";
      print "document.dialog.deck_programs.value = '"  . mysqli_real_escape_string($db, $a_cyberdeck['deck_programs'])  . "';\n";
      print "document.dialog.deck_access.value = '"    . mysqli_real_escape_string($db, $a_cyberdeck['deck_access'])    . "';\n";
      print "document.dialog.deck_avail.value = '"     . mysqli_real_escape_string($db, $a_cyberdeck['deck_avail'])     . "';\n";
      print "document.dialog.deck_perm.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_perm'])      . "';\n";
      print "document.dialog.deck_basetime.value = '"  . mysqli_real_escape_string($db, $a_cyberdeck['deck_basetime'])  . "';\n";
      print "document.dialog.deck_duration.value = '"  . mysqli_real_escape_string($db, $a_cyberdeck['deck_duration'])  . "';\n";
      print "document.dialog.deck_index.value = '"     . mysqli_real_escape_string($db, $a_cyberdeck['deck_index'])     . "';\n";
      print "document.dialog.deck_cost.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_cost'])      . "';\n";
      print "document.dialog.deck_book.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_book'])      . "';\n";
      print "document.dialog.deck_page.value = '"      . mysqli_real_escape_string($db, $a_cyberdeck['deck_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
