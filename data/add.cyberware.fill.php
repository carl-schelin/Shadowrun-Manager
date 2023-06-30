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

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberware");

      $q_string  = "select ware_class,ware_name,ware_rating,ware_multiply,ware_essence,ware_capacity,ware_avail,";
      $q_string .= "ware_perm,ware_basetime,ware_duration,ware_index,ware_cost,ware_book,ware_page ";
      $q_string .= "from cyberware ";
      $q_string .= "where ware_id = " . $formVars['id'];
      $q_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_cyberware = mysql_fetch_array($q_cyberware);
      mysql_free_result($q_cyberware);

      print "document.dialog.ware_class.value = '"     . mysql_real_escape_string($a_cyberware['ware_class'])     . "';\n";
      print "document.dialog.ware_name.value = '"      . mysql_real_escape_string($a_cyberware['ware_name'])      . "';\n";
      print "document.dialog.ware_rating.value = '"    . mysql_real_escape_string($a_cyberware['ware_rating'])    . "';\n";
      print "document.dialog.ware_essence.value = '"   . mysql_real_escape_string($a_cyberware['ware_essence'])   . "';\n";
      print "document.dialog.ware_capacity.value = '"  . mysql_real_escape_string($a_cyberware['ware_capacity'])  . "';\n";
      print "document.dialog.ware_avail.value = '"     . mysql_real_escape_string($a_cyberware['ware_avail'])     . "';\n";
      print "document.dialog.ware_perm.value = '"      . mysql_real_escape_string($a_cyberware['ware_perm'])      . "';\n";
      print "document.dialog.ware_basetime.value = '"  . mysql_real_escape_string($a_cyberware['ware_basetime'])  . "';\n";
      print "document.dialog.ware_duration.value = '"  . mysql_real_escape_string($a_cyberware['ware_duration'])  . "';\n";
      print "document.dialog.ware_index.value = '"     . mysql_real_escape_string($a_cyberware['ware_index'])     . "';\n";
      print "document.dialog.ware_cost.value = '"      . mysql_real_escape_string($a_cyberware['ware_cost'])      . "';\n";
      print "document.dialog.ware_book.value = '"      . mysql_real_escape_string($a_cyberware['ware_book'])      . "';\n";
      print "document.dialog.ware_page.value = '"      . mysql_real_escape_string($a_cyberware['ware_page'])      . "';\n";

      if ($a_cyberware['ware_multiply']) {
        print "document.dialog.ware_multiply.checked = true;\n";
      } else {
        print "document.dialog.ware_multiply.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
