<?php
# Script: add.armor.fill.php
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
    $package = "add.armor.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from armor");

      $q_string  = "select arm_class,arm_name,arm_rating,arm_ballistic,arm_impact,arm_capacity,arm_avail,arm_perm,arm_basetime,arm_duration,arm_index,arm_cost,arm_book,arm_page ";
      $q_string .= "from armor ";
      $q_string .= "where arm_id = " . $formVars['id'];
      $q_armor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_armor = mysqli_fetch_array($q_armor);
      mysql_free_result($q_armor);

      print "document.dialog.arm_class.value = '"       . mysql_real_escape_string($a_armor['arm_class'])       . "';\n";
      print "document.dialog.arm_name.value = '"        . mysql_real_escape_string($a_armor['arm_name'])        . "';\n";
      print "document.dialog.arm_rating.value = '"      . mysql_real_escape_string($a_armor['arm_rating'])      . "';\n";
      print "document.dialog.arm_ballistic.value = '"   . mysql_real_escape_string($a_armor['arm_ballistic'])   . "';\n";
      print "document.dialog.arm_impact.value = '"      . mysql_real_escape_string($a_armor['arm_impact'])      . "';\n";
      print "document.dialog.arm_capacity.value = '"    . mysql_real_escape_string($a_armor['arm_capacity'])    . "';\n";
      print "document.dialog.arm_avail.value = '"       . mysql_real_escape_string($a_armor['arm_avail'])       . "';\n";
      print "document.dialog.arm_perm.value = '"        . mysql_real_escape_string($a_armor['arm_perm'])        . "';\n";
      print "document.dialog.arm_basetime.value = '"    . mysql_real_escape_string($a_armor['arm_basetime'])    . "';\n";
      print "document.dialog.arm_duration.value = '"    . mysql_real_escape_string($a_armor['arm_duration'])    . "';\n";
      print "document.dialog.arm_index.value = '"       . mysql_real_escape_string($a_armor['arm_index'])       . "';\n";
      print "document.dialog.arm_cost.value = '"        . mysql_real_escape_string($a_armor['arm_cost'])        . "';\n";
      print "document.dialog.arm_book.value = '"        . mysql_real_escape_string($a_armor['arm_book'])        . "';\n";
      print "document.dialog.arm_page.value = '"        . mysql_real_escape_string($a_armor['arm_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
