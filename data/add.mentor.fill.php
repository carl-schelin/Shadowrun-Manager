<?php
# Script: add.mentor.fill.php
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
    $package = "add.mentor.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from mentor");

      $q_string  = "select mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,mentor_book,mentor_page ";
      $q_string .= "from mentor ";
      $q_string .= "where mentor_id = " . $formVars['id'];
      $q_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_mentor = mysql_fetch_array($q_mentor);
      mysql_free_result($q_mentor);

      print "document.dialog.mentor_name.value = '"         . mysql_real_escape_string($a_mentor['mentor_name'])         . "';\n";
      print "document.dialog.mentor_all.value = '"          . mysql_real_escape_string($a_mentor['mentor_all'])          . "';\n";
      print "document.dialog.mentor_mage.value = '"         . mysql_real_escape_string($a_mentor['mentor_mage'])         . "';\n";
      print "document.dialog.mentor_adept.value = '"        . mysql_real_escape_string($a_mentor['mentor_adept'])        . "';\n";
      print "document.dialog.mentor_disadvantage.value = '" . mysql_real_escape_string($a_mentor['mentor_disadvantage']) . "';\n";
      print "document.dialog.mentor_book.value = '"         . mysql_real_escape_string($a_mentor['mentor_book'])         . "';\n";
      print "document.dialog.mentor_page.value = '"         . mysql_real_escape_string($a_mentor['mentor_page'])         . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
