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

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from ammo");

      $q_string  = "select ammo_class,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_ap,";
      $q_string .= "ammo_blast,ammo_armor,ammo_avail,ammo_perm,ammo_cost,ammo_book,ammo_page ";
      $q_string .= "from ammo ";
      $q_string .= "where ammo_id = " . $formVars['id'];
      $q_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_ammo = mysql_fetch_array($q_ammo);
      mysql_free_result($q_ammo);

      print "document.dialog.ammo_class.value = '"      . mysql_real_escape_string($a_ammo['ammo_class'])      . "';\n";
      print "document.dialog.ammo_name.value = '"       . mysql_real_escape_string($a_ammo['ammo_name'])       . "';\n";
      print "document.dialog.ammo_rounds.value = '"     . mysql_real_escape_string($a_ammo['ammo_rounds'])     . "';\n";
      print "document.dialog.ammo_rating.value = '"     . mysql_real_escape_string($a_ammo['ammo_rating'])     . "';\n";
      print "document.dialog.ammo_mod.value = '"        . mysql_real_escape_string($a_ammo['ammo_mod'])        . "';\n";
      print "document.dialog.ammo_ap.value = '"         . mysql_real_escape_string($a_ammo['ammo_ap'])         . "';\n";
      print "document.dialog.ammo_blast.value = '"      . mysql_real_escape_string($a_ammo['ammo_blast'])      . "';\n";
      print "document.dialog.ammo_armor.value = '"      . mysql_real_escape_string($a_ammo['ammo_armor'])      . "';\n";
      print "document.dialog.ammo_avail.value = '"      . mysql_real_escape_string($a_ammo['ammo_avail'])      . "';\n";
      print "document.dialog.ammo_perm.value = '"       . mysql_real_escape_string($a_ammo['ammo_perm'])       . "';\n";
      print "document.dialog.ammo_cost.value = '"       . mysql_real_escape_string($a_ammo['ammo_cost'])       . "';\n";
      print "document.dialog.ammo_book.value = '"       . mysql_real_escape_string($a_ammo['ammo_book'])       . "';\n";
      print "document.dialog.ammo_page.value = '"       . mysql_real_escape_string($a_ammo['ammo_page'])       . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
