<?php
# Script: add.bioware.fill.php
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
    $package = "add.bioware.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from bioware");

      $q_string  = "select bio_class,bio_name,bio_rating,bio_essence,bio_avail,bio_perm,bio_basetime,bio_duration,bio_index,bio_cost,bio_book,bio_page ";
      $q_string .= "from bioware ";
      $q_string .= "where bio_id = " . $formVars['id'];
      $q_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_bioware = mysqli_fetch_array($q_bioware);
      mysql_free_result($q_bioware);

      print "document.dialog.bio_class.value = '"    . mysql_real_escape_string($a_bioware['bio_class'])    . "';\n";
      print "document.dialog.bio_name.value = '"     . mysql_real_escape_string($a_bioware['bio_name'])     . "';\n";
      print "document.dialog.bio_rating.value = '"   . mysql_real_escape_string($a_bioware['bio_rating']) . "';\n";
      print "document.dialog.bio_essence.value = '"  . mysql_real_escape_string($a_bioware['bio_essence'])  . "';\n";
      print "document.dialog.bio_avail.value = '"    . mysql_real_escape_string($a_bioware['bio_avail'])    . "';\n";
      print "document.dialog.bio_perm.value = '"     . mysql_real_escape_string($a_bioware['bio_perm'])     . "';\n";
      print "document.dialog.bio_basetime.value = '" . mysql_real_escape_string($a_bioware['bio_basetime']) . "';\n";
      print "document.dialog.bio_duration.value = '" . mysql_real_escape_string($a_bioware['bio_duration']) . "';\n";
      print "document.dialog.bio_index.value = '"    . mysql_real_escape_string($a_bioware['bio_index'])    . "';\n";
      print "document.dialog.bio_cost.value = '"     . mysql_real_escape_string($a_bioware['bio_cost'])     . "';\n";
      print "document.dialog.bio_book.value = '"     . mysql_real_escape_string($a_bioware['bio_book'])     . "';\n";
      print "document.dialog.bio_page.value = '"     . mysql_real_escape_string($a_bioware['bio_page'])     . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
