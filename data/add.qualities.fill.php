<?php
# Script: add.qualities.fill.php
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
    $package = "add.qualities.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from qualities");

      $q_string  = "select qual_name,qual_value,qual_desc,qual_book,qual_page ";
      $q_string .= "from qualities ";
      $q_string .= "where qual_id = " . $formVars['id'];
      $q_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_qualities = mysql_fetch_array($q_qualities);
      mysql_free_result($q_qualities);

      print "document.dialog.qual_name.value = '"  . mysql_real_escape_string($a_qualities['qual_name'])  . "';\n";
      print "document.dialog.qual_value.value = '" . mysql_real_escape_string($a_qualities['qual_value']) . "';\n";
      print "document.dialog.qual_desc.value = '"  . mysql_real_escape_string($a_qualities['qual_desc'])  . "';\n";
      print "document.dialog.qual_book.value = '"  . mysql_real_escape_string($a_qualities['qual_book'])  . "';\n";
      print "document.dialog.qual_page.value = '"  . mysql_real_escape_string($a_qualities['qual_page'])  . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
