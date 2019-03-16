<?php
# Script: add.weakness.fill.php
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
    $package = "add.weakness.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from weakness");

      $q_string  = "select weak_name,weak_description,weak_book,weak_page ";
      $q_string .= "from weakness ";
      $q_string .= "where weak_id = " . $formVars['id'];
      $q_weakness = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_weakness = mysql_fetch_array($q_weakness);
      mysql_free_result($q_weakness);

      print "document.dialog.weak_name.value = '"         . mysql_real_escape_string($a_weakness['weak_name'])        . "';\n";
      print "document.dialog.weak_description.value = '"  . mysql_real_escape_string($a_weakness['weak_description']) . "';\n";
      print "document.dialog.weak_book.value = '"         . mysql_real_escape_string($a_weakness['weak_book'])        . "';\n";
      print "document.dialog.weak_page.value = '"         . mysql_real_escape_string($a_weakness['weak_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
