<?php
# Script: add.spells.fill.php
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
    $package = "add.spells.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from spells");

      $q_string  = "select spell_name,spell_group,spell_class,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,spell_book,spell_page  ";
      $q_string .= "from spells ";
      $q_string .= "where spell_id = " . $formVars['id'];
      $q_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_spells = mysql_fetch_array($q_spells);
      mysql_free_result($q_spells);

      print "document.dialog.spell_name.value = '"     . mysql_real_escape_string($a_spells['spell_name'])     . "';\n";
      print "document.dialog.spell_group.value = '"    . mysql_real_escape_string($a_spells['spell_group'])    . "';\n";
      print "document.dialog.spell_class.value = '"    . mysql_real_escape_string($a_spells['spell_class'])    . "';\n";
      print "document.dialog.spell_type.value = '"     . mysql_real_escape_string($a_spells['spell_type'])     . "';\n";
      print "document.dialog.spell_test.value = '"     . mysql_real_escape_string($a_spells['spell_test'])     . "';\n";
      print "document.dialog.spell_range.value = '"    . mysql_real_escape_string($a_spells['spell_range'])    . "';\n";
      print "document.dialog.spell_damage.value = '"   . mysql_real_escape_string($a_spells['spell_damage'])   . "';\n";
      print "document.dialog.spell_duration.value = '" . mysql_real_escape_string($a_spells['spell_duration']) . "';\n";
      print "document.dialog.spell_drain.value = '"    . mysql_real_escape_string($a_spells['spell_drain'])    . "';\n";
      print "document.dialog.spell_book.value = '"     . mysql_real_escape_string($a_spells['spell_book'])     . "';\n";
      print "document.dialog.spell_page.value = '"     . mysql_real_escape_string($a_spells['spell_page'])     . "';\n";

      if ($a_spells['spell_force'] == 1) {
        print "document.dialog.spell_force.checked = true;\n";
      } else {
        print "document.dialog.spell_force.checked = false;\n";
      }
      if ($a_spells['spell_force'] == 2) {
        print "document.dialog.spell_half.checked = true;\n";
      } else {
        print "document.dialog.spell_half.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
