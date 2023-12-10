<?php
# Script: add.ammo.fill.php
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
    $package = "add.ammo.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from ammo");

      $q_string  = "select ammo_class,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_ap,ammo_close,ammo_near,";
      $q_string .= "ammo_blast,ammo_armor,ammo_avail,ammo_perm,ammo_basetime,ammo_duration,ammo_index,ammo_cost,ammo_book,ammo_page ";
      $q_string .= "from ammo ";
      $q_string .= "where ammo_id = " . $formVars['id'];
      $q_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_ammo = mysqli_fetch_array($q_ammo);
      mysqli_free_result($q_ammo);

      print "document.dialog.ammo_class.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_class'])      . "';\n";
      print "document.dialog.ammo_name.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_name'])       . "';\n";
      print "document.dialog.ammo_rounds.value = '"     . mysqli_real_escape_string($db, $a_ammo['ammo_rounds'])     . "';\n";
      print "document.dialog.ammo_rating.value = '"     . mysqli_real_escape_string($db, $a_ammo['ammo_rating'])     . "';\n";
      print "document.dialog.ammo_mod.value = '"        . mysqli_real_escape_string($db, $a_ammo['ammo_mod'])        . "';\n";
      print "document.dialog.ammo_close.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_close'])      . "';\n";
      print "document.dialog.ammo_near.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_near'])       . "';\n";
      print "document.dialog.ammo_ap.value = '"         . mysqli_real_escape_string($db, $a_ammo['ammo_ap'])         . "';\n";
      print "document.dialog.ammo_blast.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_blast'])      . "';\n";
      print "document.dialog.ammo_armor.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_armor'])      . "';\n";
      print "document.dialog.ammo_avail.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_avail'])      . "';\n";
      print "document.dialog.ammo_perm.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_perm'])       . "';\n";
      print "document.dialog.ammo_basetime.value = '"   . mysqli_real_escape_string($db, $a_ammo['ammo_basetime'])   . "';\n";
      print "document.dialog.ammo_duration.value = '"   . mysqli_real_escape_string($db, $a_ammo['ammo_duration'])   . "';\n";
      print "document.dialog.ammo_index.value = '"      . mysqli_real_escape_string($db, $a_ammo['ammo_index'])      . "';\n";
      print "document.dialog.ammo_cost.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_cost'])       . "';\n";
      print "document.dialog.ammo_book.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_book'])       . "';\n";
      print "document.dialog.ammo_page.value = '"       . mysqli_real_escape_string($db, $a_ammo['ammo_page'])       . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
