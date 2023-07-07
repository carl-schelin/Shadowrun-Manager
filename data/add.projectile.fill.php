<?php
# Script: add.projectile.fill.php
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
    $package = "add.projectile.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from projectile");

      $q_string  = "select proj_class,proj_name,proj_acc,proj_damage,proj_type,proj_rating,proj_ap,proj_avail,proj_ar1,proj_ar2,proj_ar3,proj_ar4,proj_ar5, ";
      $q_string .= "proj_perm,proj_basetime,proj_duration,proj_index,proj_cost,proj_book,proj_page ";
      $q_string .= "from projectile ";
      $q_string .= "where proj_id = " . $formVars['id'];
      $q_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_projectile = mysql_fetch_array($q_projectile);
      mysql_free_result($q_projectile);

      print "document.dialog.proj_class.value = '"    . mysql_real_escape_string($a_projectile['proj_class'])    . "';\n";
      print "document.dialog.proj_name.value = '"     . mysql_real_escape_string($a_projectile['proj_name'])     . "';\n";
      print "document.dialog.proj_rating.value = '"   . mysql_real_escape_string($a_projectile['proj_rating'])   . "';\n";
      print "document.dialog.proj_acc.value = '"      . mysql_real_escape_string($a_projectile['proj_acc'])      . "';\n";
      print "document.dialog.proj_ar1.value = '"      . mysql_real_escape_string($a_projectile['proj_ar1'])      . "';\n";
      print "document.dialog.proj_ar2.value = '"      . mysql_real_escape_string($a_projectile['proj_ar2'])      . "';\n";
      print "document.dialog.proj_ar3.value = '"      . mysql_real_escape_string($a_projectile['proj_ar3'])      . "';\n";
      print "document.dialog.proj_ar4.value = '"      . mysql_real_escape_string($a_projectile['proj_ar4'])      . "';\n";
      print "document.dialog.proj_ar5.value = '"      . mysql_real_escape_string($a_projectile['proj_ar5'])      . "';\n";
      print "document.dialog.proj_damage.value = '"   . mysql_real_escape_string($a_projectile['proj_damage'])   . "';\n";
      print "document.dialog.proj_type.value = '"     . mysql_real_escape_string($a_projectile['proj_type'])     . "';\n";
      print "document.dialog.proj_ap.value = '"       . mysql_real_escape_string($a_projectile['proj_ap'])       . "';\n";
      print "document.dialog.proj_avail.value = '"    . mysql_real_escape_string($a_projectile['proj_avail'])    . "';\n";
      print "document.dialog.proj_perm.value = '"     . mysql_real_escape_string($a_projectile['proj_perm'])     . "';\n";
      print "document.dialog.proj_basetime.value = '" . mysql_real_escape_string($a_projectile['proj_basetime']) . "';\n";
      print "document.dialog.proj_duration.value = '" . mysql_real_escape_string($a_projectile['proj_duration']) . "';\n";
      print "document.dialog.proj_index.value = '"    . mysql_real_escape_string($a_projectile['proj_index'])    . "';\n";
      print "document.dialog.proj_cost.value = '"     . mysql_real_escape_string($a_projectile['proj_cost'])     . "';\n";
      print "document.dialog.proj_book.value = '"     . mysql_real_escape_string($a_projectile['proj_book'])     . "';\n";
      print "document.dialog.proj_page.value = '"     . mysql_real_escape_string($a_projectile['proj_page'])     . "';\n";

      if ($a_projectile['proj_strength']) {
        print "document.dialog.proj_strength.checked = true;\n";
      } else {
        print "document.dialog.proj_strength.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
