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

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberdeck");

      $q_string  = "select deck_brand,deck_model,deck_rating,deck_attack,deck_sleaze,deck_data,deck_firewall,";
      $q_string .= "deck_programs,deck_access,deck_avail,deck_perm,deck_cost,deck_book,deck_page,";
      $q_string .= "deck_persona,deck_hardening,deck_memory,deck_storage,deck_load,deck_io,deck_basetime,deck_duration ";
      $q_string .= "from cyberdeck ";
      $q_string .= "where deck_id = " . $formVars['id'];
      $q_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_cyberdeck = mysql_fetch_array($q_cyberdeck);
      mysql_free_result($q_cyberdeck);

      print "document.dialog.deck_brand.value = '"     . mysql_real_escape_string($a_cyberdeck['deck_brand'])     . "';\n";
      print "document.dialog.deck_model.value = '"     . mysql_real_escape_string($a_cyberdeck['deck_model'])     . "';\n";
      print "document.dialog.deck_rating.value = '"    . mysql_real_escape_string($a_cyberdeck['deck_rating'])    . "';\n";
      print "document.dialog.deck_attack.value = '"    . mysql_real_escape_string($a_cyberdeck['deck_attack'])    . "';\n";
      print "document.dialog.deck_sleaze.value = '"    . mysql_real_escape_string($a_cyberdeck['deck_sleaze'])    . "';\n";
      print "document.dialog.deck_data.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_data'])      . "';\n";
      print "document.dialog.deck_firewall.value = '"  . mysql_real_escape_string($a_cyberdeck['deck_firewall'])  . "';\n";
      print "document.dialog.deck_persona.value = '"   . mysql_real_escape_string($a_cyberdeck['deck_persona'])   . "';\n";
      print "document.dialog.deck_hardening.value = '" . mysql_real_escape_string($a_cyberdeck['deck_hardening']) . "';\n";
      print "document.dialog.deck_memory.value = '"    . mysql_real_escape_string($a_cyberdeck['deck_memory'])    . "';\n";
      print "document.dialog.deck_storage.value = '"   . mysql_real_escape_string($a_cyberdeck['deck_storage'])   . "';\n";
      print "document.dialog.deck_load.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_load'])      . "';\n";
      print "document.dialog.deck_io.value = '"        . mysql_real_escape_string($a_cyberdeck['deck_io'])        . "';\n";
      print "document.dialog.deck_programs.value = '"  . mysql_real_escape_string($a_cyberdeck['deck_programs'])  . "';\n";
      print "document.dialog.deck_access.value = '"    . mysql_real_escape_string($a_cyberdeck['deck_access'])    . "';\n";
      print "document.dialog.deck_avail.value = '"     . mysql_real_escape_string($a_cyberdeck['deck_avail'])     . "';\n";
      print "document.dialog.deck_perm.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_perm'])      . "';\n";
      print "document.dialog.deck_basetime.value = '"  . mysql_real_escape_string($a_cyberdeck['deck_basetime'])  . "';\n";
      print "document.dialog.deck_duration.value = '"  . mysql_real_escape_string($a_cyberdeck['deck_duration'])  . "';\n";
      print "document.dialog.deck_cost.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_cost'])      . "';\n";
      print "document.dialog.deck_book.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_book'])      . "';\n";
      print "document.dialog.deck_page.value = '"      . mysql_real_escape_string($a_cyberdeck['deck_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
