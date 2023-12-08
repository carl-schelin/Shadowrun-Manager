<?php
# Script: add.complexform.fill.php
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
    $package = "add.complexform.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from complexform");

      $q_string  = "select form_name,form_target,form_duration,form_level,form_fading,form_book,form_page ";
      $q_string .= "from complexform ";
      $q_string .= "where form_id = " . $formVars['id'];
      $q_complexform = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_complexform = mysqli_fetch_array($q_complexform);
      mysql_free_result($q_complexform);

      print "document.dialog.form_name.value = '"     . mysql_real_escape_string($a_complexform['form_name'])     . "';\n";
      print "document.dialog.form_target.value = '"   . mysql_real_escape_string($a_complexform['form_target'])   . "';\n";
      print "document.dialog.form_duration.value = '" . mysql_real_escape_string($a_complexform['form_duration']) . "';\n";
      print "document.dialog.form_fading.value = '"   . mysql_real_escape_string($a_complexform['form_fading'])   . "';\n";
      print "document.dialog.form_book.value = '"     . mysql_real_escape_string($a_complexform['form_book'])     . "';\n";
      print "document.dialog.form_page.value = '"     . mysql_real_escape_string($a_complexform['form_page'])     . "';\n";

      if ($a_complexform['form_level']) {
        print "document.dialog.form_level.checked = true;\n";
      } else {
        print "document.dialog.form_level.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
