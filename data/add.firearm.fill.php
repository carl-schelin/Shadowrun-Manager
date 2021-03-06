<?php
# Script: add.firearm.fill.php
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
    $package = "add.firearm.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from firearm");

      $q_string  = "select fa_class,fa_name,fa_acc,fa_damage,fa_type,fa_flag,fa_ap,fa_mode1,";
      $q_string .= "fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,";
      $q_string .= "fa_cost,fa_book,fa_page ";
      $q_string .= "from firearms ";
      $q_string .= "where fa_id = " . $formVars['id'];
      $q_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_firearms = mysql_fetch_array($q_firearms);
      mysql_free_result($q_firearms);

      print "document.dialog.fa_class.value = '"  . mysql_real_escape_string($a_firearms['fa_class'])  . "';\n";
      print "document.dialog.fa_name.value = '"   . mysql_real_escape_string($a_firearms['fa_name'])   . "';\n";
      print "document.dialog.fa_acc.value = '"    . mysql_real_escape_string($a_firearms['fa_acc'])    . "';\n";
      print "document.dialog.fa_damage.value = '" . mysql_real_escape_string($a_firearms['fa_damage']) . "';\n";
      print "document.dialog.fa_type.value = '"   . mysql_real_escape_string($a_firearms['fa_type'])   . "';\n";
      print "document.dialog.fa_flag.value = '"   . mysql_real_escape_string($a_firearms['fa_flag'])   . "';\n";
      print "document.dialog.fa_ap.value = '"     . mysql_real_escape_string($a_firearms['fa_ap'])     . "';\n";
      print "document.dialog.fa_mode1.value = '"  . mysql_real_escape_string($a_firearms['fa_mode1'])  . "';\n";
      print "document.dialog.fa_mode2.value = '"  . mysql_real_escape_string($a_firearms['fa_mode2'])  . "';\n";
      print "document.dialog.fa_mode3.value = '"  . mysql_real_escape_string($a_firearms['fa_mode3'])  . "';\n";
      print "document.dialog.fa_rc.value = '"     . mysql_real_escape_string($a_firearms['fa_rc'])     . "';\n";
      print "document.dialog.fa_fullrc.value = '" . mysql_real_escape_string($a_firearms['fa_fullrc']) . "';\n";
      print "document.dialog.fa_ammo1.value = '"  . mysql_real_escape_string($a_firearms['fa_ammo1'])  . "';\n";
      print "document.dialog.fa_clip1.value = '"  . mysql_real_escape_string($a_firearms['fa_clip1'])  . "';\n";
      print "document.dialog.fa_ammo2.value = '"  . mysql_real_escape_string($a_firearms['fa_ammo2'])  . "';\n";
      print "document.dialog.fa_clip2.value = '"  . mysql_real_escape_string($a_firearms['fa_clip2'])  . "';\n";
      print "document.dialog.fa_avail.value = '"  . mysql_real_escape_string($a_firearms['fa_avail'])  . "';\n";
      print "document.dialog.fa_perm.value = '"   . mysql_real_escape_string($a_firearms['fa_perm'])   . "';\n";
      print "document.dialog.fa_cost.value = '"   . mysql_real_escape_string($a_firearms['fa_cost'])   . "';\n";
      print "document.dialog.fa_book.value = '"   . mysql_real_escape_string($a_firearms['fa_book'])   . "';\n";
      print "document.dialog.fa_page.value = '"   . mysql_real_escape_string($a_firearms['fa_page'])   . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
