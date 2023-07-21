<?php
# Script: add.melee.fill.php
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
    $package = "add.melee.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from melee");

      $q_string  = "select melee_class,melee_name,melee_acc,melee_reach,melee_damage,melee_type,melee_ar1,melee_ar2,melee_ar3,melee_ar4,melee_ar5,";
      $q_string .= "melee_flag,melee_strength,melee_ap,melee_avail,melee_perm,melee_basetime,melee_duration,melee_index,";
      $q_string .= "melee_cost,melee_book,melee_page ";
      $q_string .= "from melee ";
      $q_string .= "where melee_id = " . $formVars['id'];
      $q_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_melee = mysql_fetch_array($q_melee);
      mysql_free_result($q_melee);

      print "document.dialog.melee_class.value = '"    . mysql_real_escape_string($a_melee['melee_class'])    . "';\n";
      print "document.dialog.melee_name.value = '"     . mysql_real_escape_string($a_melee['melee_name'])     . "';\n";
      print "document.dialog.melee_acc.value = '"      . mysql_real_escape_string($a_melee['melee_acc'])      . "';\n";
      print "document.dialog.melee_reach.value = '"    . mysql_real_escape_string($a_melee['melee_reach'])    . "';\n";
      print "document.dialog.melee_ar1.value = '"      . mysql_real_escape_string($a_melee['melee_ar1'])      . "';\n";
      print "document.dialog.melee_ar2.value = '"      . mysql_real_escape_string($a_melee['melee_ar2'])      . "';\n";
      print "document.dialog.melee_ar3.value = '"      . mysql_real_escape_string($a_melee['melee_ar3'])      . "';\n";
      print "document.dialog.melee_ar4.value = '"      . mysql_real_escape_string($a_melee['melee_ar4'])      . "';\n";
      print "document.dialog.melee_ar5.value = '"      . mysql_real_escape_string($a_melee['melee_ar5'])      . "';\n";
      print "document.dialog.melee_damage.value = '"   . mysql_real_escape_string($a_melee['melee_damage'])   . "';\n";
      print "document.dialog.melee_type.value = '"     . mysql_real_escape_string($a_melee['melee_type'])     . "';\n";
      print "document.dialog.melee_flag.value = '"     . mysql_real_escape_string($a_melee['melee_flag'])     . "';\n";
      print "document.dialog.melee_ap.value = '"       . mysql_real_escape_string($a_melee['melee_ap'])       . "';\n";
      print "document.dialog.melee_avail.value = '"    . mysql_real_escape_string($a_melee['melee_avail'])    . "';\n";
      print "document.dialog.melee_perm.value = '"     . mysql_real_escape_string($a_melee['melee_perm'])     . "';\n";
      print "document.dialog.melee_basetime.value = '" . mysql_real_escape_string($a_melee['melee_basetime']) . "';\n";
      print "document.dialog.melee_duration.value = '" . mysql_real_escape_string($a_melee['melee_duration']) . "';\n";
      print "document.dialog.melee_index.value = '"    . mysql_real_escape_string($a_melee['melee_index'])    . "';\n";
      print "document.dialog.melee_cost.value = '"     . mysql_real_escape_string($a_melee['melee_cost'])     . "';\n";
      print "document.dialog.melee_book.value = '"     . mysql_real_escape_string($a_melee['melee_book'])     . "';\n";
      print "document.dialog.melee_page.value = '"     . mysql_real_escape_string($a_melee['melee_page'])     . "';\n";

      if ($a_melee['melee_strength'] > 0) {
        print "document.dialog.melee_strength.checked = true;\n";
      } else {
        print "document.dialog.melee_strength.checked = false;\n";
      }
      if ($a_melee['melee_strength'] == 2) {
        print "document.dialog.melee_half.checked = true;\n";
      } else {
        print "document.dialog.melee_half.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
