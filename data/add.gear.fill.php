<?php
# Script: add.gear.fill.php
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
    $package = "add.gear.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from gear");

      $q_string  = "select gear_class,gear_name,gear_rating,gear_capacity,gear_avail,gear_perm,gear_basetime,gear_duration,gear_index,gear_cost,gear_book,gear_page ";
      $q_string .= "from gear ";
      $q_string .= "where gear_id = " . $formVars['id'];
      $q_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_gear = mysqli_fetch_array($q_gear);
      mysqli_free_result($q_gear);

      print "document.dialog.gear_class.value = '"       . mysqli_real_escape_string($db, $a_gear['gear_class'])         . "';\n";
      print "document.dialog.gear_name.value = '"        . mysqli_real_escape_string($db, $a_gear['gear_name'])          . "';\n";
      print "document.dialog.gear_rating.value = '"      . mysqli_real_escape_string($db, $a_gear['gear_rating'])        . "';\n";
      print "document.dialog.gear_capacity.value = '"    . mysqli_real_escape_string($db, $a_gear['gear_capacity'])      . "';\n";
      print "document.dialog.gear_avail.value = '"       . mysqli_real_escape_string($db, $a_gear['gear_avail'])         . "';\n";
      print "document.dialog.gear_perm.value = '"        . mysqli_real_escape_string($db, $a_gear['gear_perm'])          . "';\n";
      print "document.dialog.gear_basetime.value = '"    . mysqli_real_escape_string($db, $a_gear['gear_basetime'])      . "';\n";
      print "document.dialog.gear_duration.value = '"    . mysqli_real_escape_string($db, $a_gear['gear_duration'])      . "';\n";
      print "document.dialog.gear_index.value = '"       . mysqli_real_escape_string($db, $a_gear['gear_index'])         . "';\n";
      print "document.dialog.gear_cost.value = '"        . mysqli_real_escape_string($db, $a_gear['gear_cost'])          . "';\n";
      print "document.dialog.gear_book.value = '"        . mysqli_real_escape_string($db, $a_gear['gear_book'])          . "';\n";
      print "document.dialog.gear_page.value = '"        . mysqli_real_escape_string($db, $a_gear['gear_page'])          . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
