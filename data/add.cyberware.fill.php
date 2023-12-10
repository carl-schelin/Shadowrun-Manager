<?php
# Script: add.cyberware.fill.php
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
    $package = "add.cyberware.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberware");

      $q_string  = "select ware_class,ware_name,ware_rating,ware_multiply,ware_essence,ware_capacity,ware_avail,";
      $q_string .= "ware_perm,ware_basetime,ware_duration,ware_index,ware_legality,ware_cost,ware_book,ware_page ";
      $q_string .= "from cyberware ";
      $q_string .= "where ware_id = " . $formVars['id'];
      $q_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_cyberware = mysqli_fetch_array($q_cyberware);
      mysqli_free_result($q_cyberware);

      print "document.dialog.ware_class.value = '"     . mysqli_real_escape_string($db, $a_cyberware['ware_class'])     . "';\n";
      print "document.dialog.ware_name.value = '"      . mysqli_real_escape_string($db, $a_cyberware['ware_name'])      . "';\n";
      print "document.dialog.ware_rating.value = '"    . mysqli_real_escape_string($db, $a_cyberware['ware_rating'])    . "';\n";
      print "document.dialog.ware_essence.value = '"   . mysqli_real_escape_string($db, $a_cyberware['ware_essence'])   . "';\n";
      print "document.dialog.ware_capacity.value = '"  . mysqli_real_escape_string($db, $a_cyberware['ware_capacity'])  . "';\n";
      print "document.dialog.ware_avail.value = '"     . mysqli_real_escape_string($db, $a_cyberware['ware_avail'])     . "';\n";
      print "document.dialog.ware_perm.value = '"      . mysqli_real_escape_string($db, $a_cyberware['ware_perm'])      . "';\n";
      print "document.dialog.ware_basetime.value = '"  . mysqli_real_escape_string($db, $a_cyberware['ware_basetime'])  . "';\n";
      print "document.dialog.ware_duration.value = '"  . mysqli_real_escape_string($db, $a_cyberware['ware_duration'])  . "';\n";
      print "document.dialog.ware_index.value = '"     . mysqli_real_escape_string($db, $a_cyberware['ware_index'])     . "';\n";
      print "document.dialog.ware_legality.value = '"  . mysqli_real_escape_string($db, $a_cyberware['ware_legality'])  . "';\n";
      print "document.dialog.ware_cost.value = '"      . mysqli_real_escape_string($db, $a_cyberware['ware_cost'])      . "';\n";
      print "document.dialog.ware_book.value = '"      . mysqli_real_escape_string($db, $a_cyberware['ware_book'])      . "';\n";
      print "document.dialog.ware_page.value = '"      . mysqli_real_escape_string($db, $a_cyberware['ware_page'])      . "';\n";

      if ($a_cyberware['ware_multiply']) {
        print "document.dialog.ware_multiply.checked = true;\n";
      } else {
        print "document.dialog.ware_multiply.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
