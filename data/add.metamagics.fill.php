<?php
# Script: add.metamagics.fill.php
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
    $package = "add.metamagics.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from metamagics");

      $q_string  = "select meta_name,meta_description,meta_book,meta_page ";
      $q_string .= "from metamagics ";
      $q_string .= "where meta_id = " . $formVars['id'];
      $q_metamagics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_metamagics = mysqli_fetch_array($q_metamagics);
      mysql_free_result($q_metamagics);

      print "document.dialog.meta_name.value = '"         . mysql_real_escape_string($a_metamagics['meta_name'])         . "';\n";
      print "document.dialog.meta_description.value = '"  . mysql_real_escape_string($a_metamagics['meta_description'])  . "';\n";
      print "document.dialog.meta_book.value = '"         . mysql_real_escape_string($a_metamagics['meta_book'])         . "';\n";
      print "document.dialog.meta_page.value = '"         . mysql_real_escape_string($a_metamagics['meta_page'])         . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
