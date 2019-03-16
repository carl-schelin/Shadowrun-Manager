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

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from accessory");

      $q_string  = "select acc_type,acc_class,acc_accessory,acc_name,acc_mount,acc_essence,acc_rating,acc_capacity,acc_avail,acc_perm,acc_cost,acc_book,acc_page ";
      $q_string .= "from accessory ";
      $q_string .= "where acc_id = " . $formVars['id'];
      $q_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_accessory = mysql_fetch_array($q_accessory);
      mysql_free_result($q_accessory);

      print "document.accessory.acc_type.value = '"      . mysql_real_escape_string($a_accessory['acc_type'])       . "';\n";
      print "document.accessory.acc_class.value = '"     . mysql_real_escape_string($a_accessory['acc_class'])      . "';\n";
      print "document.accessory.acc_accessory.value = '" . mysql_real_escape_string($a_accessory['acc_accessory'])  . "';\n";
      print "document.accessory.acc_name.value = '"      . mysql_real_escape_string($a_accessory['acc_name'])       . "';\n";
      print "document.accessory.acc_mount.value = '"     . mysql_real_escape_string($a_accessory['acc_mount'])      . "';\n";
      print "document.accessory.acc_essence.value = '"   . mysql_real_escape_string($a_accessory['acc_essence'])    . "';\n";
      print "document.accessory.acc_rating.value = '"    . mysql_real_escape_string($a_accessory['acc_rating'])     . "';\n";
      print "document.accessory.acc_capacity.value = '"  . mysql_real_escape_string($a_accessory['acc_capacity'])   . "';\n";
      print "document.accessory.acc_avail.value = '"     . mysql_real_escape_string($a_accessory['acc_avail'])      . "';\n";
      print "document.accessory.acc_perm.value = '"      . mysql_real_escape_string($a_accessory['acc_perm'])       . "';\n";
      print "document.accessory.acc_cost.value = '"      . mysql_real_escape_string($a_accessory['acc_cost'])       . "';\n";
      print "document.accessory.acc_book.value = '"      . mysql_real_escape_string($a_accessory['acc_book'])       . "';\n";
      print "document.accessory.acc_page.value = '"      . mysql_real_escape_string($a_accessory['acc_page'])       . "';\n";

      print "document.accessory.id.value = '" . $formVars['id'] . "'\n";
      print "document.accessory.update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
