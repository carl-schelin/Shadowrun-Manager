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

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from qualities");

      $q_string  = "select qual_name,qual_value,qual_desc,qual_book,qual_page ";
      $q_string .= "from qualities ";
      $q_string .= "where qual_id = " . $formVars['id'];
      $q_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_qualities = mysqli_fetch_array($q_qualities);
      mysqli_free_result($q_qualities);

      print "document.dialog.qual_name.value = '"  . mysqli_real_escape_string($db, $a_qualities['qual_name'])  . "';\n";
      print "document.dialog.qual_value.value = '" . mysqli_real_escape_string($db, $a_qualities['qual_value']) . "';\n";
      print "document.dialog.qual_desc.value = '"  . mysqli_real_escape_string($db, $a_qualities['qual_desc'])  . "';\n";
      print "document.dialog.qual_book.value = '"  . mysqli_real_escape_string($db, $a_qualities['qual_book'])  . "';\n";
      print "document.dialog.qual_page.value = '"  . mysqli_real_escape_string($db, $a_qualities['qual_page'])  . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
