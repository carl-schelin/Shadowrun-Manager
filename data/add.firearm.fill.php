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

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from firearm");

      $q_string  = "select fa_class,fa_name,fa_acc,fa_damage,fa_weight,fa_type,fa_flag,fa_conceal,fa_ap,fa_mode1,fa_mode2,";
      $q_string .= "fa_mode3,fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,fa_rc,fa_fullrc,fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,";
      $q_string .= "fa_avail,fa_perm,fa_basetime,fa_duration,fa_index,fa_cost,fa_book,fa_page ";
      $q_string .= "from firearms ";
      $q_string .= "where fa_id = " . $formVars['id'];
      $q_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_firearms = mysqli_fetch_array($q_firearms);
      mysqli_free_result($q_firearms);

      print "document.dialog.fa_class.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_class'])      . "';\n";
      print "document.dialog.fa_name.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_name'])       . "';\n";
      print "document.dialog.fa_acc.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_acc'])        . "';\n";
      print "document.dialog.fa_damage.value = '"     . mysqli_real_escape_string($db, $a_firearms['fa_damage'])     . "';\n";
      print "document.dialog.fa_weight.value = '"     . mysqli_real_escape_string($db, $a_firearms['fa_weight'])     . "';\n";
      print "document.dialog.fa_type.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_type'])       . "';\n";
      print "document.dialog.fa_flag.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_flag'])       . "';\n";
      print "document.dialog.fa_conceal.value = '"    . mysqli_real_escape_string($db, $a_firearms['fa_conceal'])    . "';\n";
      print "document.dialog.fa_ap.value = '"         . mysqli_real_escape_string($db, $a_firearms['fa_ap'])         . "';\n";
      print "document.dialog.fa_mode1.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_mode1'])      . "';\n";
      print "document.dialog.fa_mode2.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_mode2'])      . "';\n";
      print "document.dialog.fa_mode3.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_mode3'])      . "';\n";
      print "document.dialog.fa_ar1.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_ar1'])        . "';\n";
      print "document.dialog.fa_ar2.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_ar2'])        . "';\n";
      print "document.dialog.fa_ar3.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_ar3'])        . "';\n";
      print "document.dialog.fa_ar4.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_ar4'])        . "';\n";
      print "document.dialog.fa_ar5.value = '"        . mysqli_real_escape_string($db, $a_firearms['fa_ar5'])        . "';\n";
      print "document.dialog.fa_rc.value = '"         . mysqli_real_escape_string($db, $a_firearms['fa_rc'])         . "';\n";
      print "document.dialog.fa_fullrc.value = '"     . mysqli_real_escape_string($db, $a_firearms['fa_fullrc'])     . "';\n";
      print "document.dialog.fa_ammo1.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_ammo1'])      . "';\n";
      print "document.dialog.fa_clip1.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_clip1'])      . "';\n";
      print "document.dialog.fa_ammo2.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_ammo2'])      . "';\n";
      print "document.dialog.fa_clip2.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_clip2'])      . "';\n";
      print "document.dialog.fa_avail.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_avail'])      . "';\n";
      print "document.dialog.fa_perm.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_perm'])       . "';\n";
      print "document.dialog.fa_basetime.value = '"   . mysqli_real_escape_string($db, $a_firearms['fa_basetime'])   . "';\n";
      print "document.dialog.fa_duration.value = '"   . mysqli_real_escape_string($db, $a_firearms['fa_duration'])   . "';\n";
      print "document.dialog.fa_index.value = '"      . mysqli_real_escape_string($db, $a_firearms['fa_index'])      . "';\n";
      print "document.dialog.fa_cost.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_cost'])       . "';\n";
      print "document.dialog.fa_book.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_book'])       . "';\n";
      print "document.dialog.fa_page.value = '"       . mysqli_real_escape_string($db, $a_firearms['fa_page'])       . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
