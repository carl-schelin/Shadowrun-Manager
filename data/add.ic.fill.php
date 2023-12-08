<?php
# Script: add.ic.fill.php
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
    $package = "add.ic.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from ic");

      $q_string  = "select ic_name,ic_defense,ic_description,ic_book,ic_page ";
      $q_string .= "from ic ";
      $q_string .= "where ic_id = " . $formVars['id'];
      $q_ic = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_ic = mysqli_fetch_array($q_ic);
      mysql_free_result($q_ic);

      print "document.dialog.ic_name.value = '"        . mysql_real_escape_string($a_ic['ic_name'])        . "';\n";
      print "document.dialog.ic_defense.value = '"     . mysql_real_escape_string($a_ic['ic_defense'])     . "';\n";
      print "document.dialog.ic_description.value = '" . mysql_real_escape_string($a_ic['ic_description']) . "';\n";
      print "document.dialog.ic_book.value = '"        . mysql_real_escape_string($a_ic['ic_book'])        . "';\n";
      print "document.dialog.ic_page.value = '"        . mysql_real_escape_string($a_ic['ic_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
