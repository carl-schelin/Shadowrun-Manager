<?php
# Script: add.accessory.fill.php
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
    $package = "add.accessory.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from accessory");

      $q_string  = "select acc_type,acc_class,acc_accessory,acc_name,acc_mount,acc_essence,acc_rating,acc_capacity,acc_avail,acc_perm,acc_basetime,acc_duration,acc_index,acc_cost,acc_book,acc_page ";
      $q_string .= "from accessory ";
      $q_string .= "where acc_id = " . $formVars['id'];
      $q_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_accessory = mysqli_fetch_array($q_accessory);
      mysqli_free_result($q_accessory);

      print "document.dialog.acc_type.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_type'])       . "';\n";
      print "document.dialog.acc_class.value = '"     . mysqli_real_escape_string($db, $a_accessory['acc_class'])      . "';\n";
      print "document.dialog.acc_accessory.value = '" . mysqli_real_escape_string($db, $a_accessory['acc_accessory'])  . "';\n";
      print "document.dialog.acc_name.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_name'])       . "';\n";
      print "document.dialog.acc_mount.value = '"     . mysqli_real_escape_string($db, $a_accessory['acc_mount'])      . "';\n";
      print "document.dialog.acc_essence.value = '"   . mysqli_real_escape_string($db, $a_accessory['acc_essence'])    . "';\n";
      print "document.dialog.acc_rating.value = '"    . mysqli_real_escape_string($db, $a_accessory['acc_rating'])     . "';\n";
      print "document.dialog.acc_capacity.value = '"  . mysqli_real_escape_string($db, $a_accessory['acc_capacity'])   . "';\n";
      print "document.dialog.acc_avail.value = '"     . mysqli_real_escape_string($db, $a_accessory['acc_avail'])      . "';\n";
      print "document.dialog.acc_perm.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_perm'])       . "';\n";
      print "document.dialog.acc_basetime.value = '"  . mysqli_real_escape_string($db, $a_accessory['acc_basetime'])   . "';\n";
      print "document.dialog.acc_duration.value = '"  . mysqli_real_escape_string($db, $a_accessory['acc_duration'])   . "';\n";
      print "document.dialog.acc_index.value = '"     . mysqli_real_escape_string($db, $a_accessory['acc_index'])      . "';\n";
      print "document.dialog.acc_cost.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_cost'])       . "';\n";
      print "document.dialog.acc_book.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_book'])       . "';\n";
      print "document.dialog.acc_page.value = '"      . mysqli_real_escape_string($db, $a_accessory['acc_page'])       . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
