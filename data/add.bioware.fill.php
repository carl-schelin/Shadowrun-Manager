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

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from bioware");

      $q_string  = "select bio_class,bio_name,bio_rating,bio_essence,bio_avail,bio_perm,bio_basetime,bio_duration,bio_index,bio_cost,bio_book,bio_page ";
      $q_string .= "from bioware ";
      $q_string .= "where bio_id = " . $formVars['id'];
      $q_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_bioware = mysqli_fetch_array($q_bioware);
      mysqli_free_result($q_bioware);

      print "document.dialog.bio_class.value = '"    . mysqli_real_escape_string($db, $a_bioware['bio_class'])    . "';\n";
      print "document.dialog.bio_name.value = '"     . mysqli_real_escape_string($db, $a_bioware['bio_name'])     . "';\n";
      print "document.dialog.bio_rating.value = '"   . mysqli_real_escape_string($db, $a_bioware['bio_rating']) . "';\n";
      print "document.dialog.bio_essence.value = '"  . mysqli_real_escape_string($db, $a_bioware['bio_essence'])  . "';\n";
      print "document.dialog.bio_avail.value = '"    . mysqli_real_escape_string($db, $a_bioware['bio_avail'])    . "';\n";
      print "document.dialog.bio_perm.value = '"     . mysqli_real_escape_string($db, $a_bioware['bio_perm'])     . "';\n";
      print "document.dialog.bio_basetime.value = '" . mysqli_real_escape_string($db, $a_bioware['bio_basetime']) . "';\n";
      print "document.dialog.bio_duration.value = '" . mysqli_real_escape_string($db, $a_bioware['bio_duration']) . "';\n";
      print "document.dialog.bio_index.value = '"    . mysqli_real_escape_string($db, $a_bioware['bio_index'])    . "';\n";
      print "document.dialog.bio_cost.value = '"     . mysqli_real_escape_string($db, $a_bioware['bio_cost'])     . "';\n";
      print "document.dialog.bio_book.value = '"     . mysqli_real_escape_string($db, $a_bioware['bio_book'])     . "';\n";
      print "document.dialog.bio_page.value = '"     . mysqli_real_escape_string($db, $a_bioware['bio_page'])     . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
