<?php
# Script: add.books.fill.php
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
    $package = "add.books.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from versions");

      $q_string  = "select ver_book,ver_short,ver_core,ver_version,ver_year,ver_active,ver_admin ";
      $q_string .= "from versions ";
      $q_string .= "where ver_id = " . $formVars['id'];
      $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_versions = mysqli_fetch_array($q_versions);
      mysql_free_result($q_versions);

      print "document.dialog.ver_book.value = '"    . mysql_real_escape_string($a_versions['ver_book'])    . "';\n";
      print "document.dialog.ver_short.value = '"   . mysql_real_escape_string($a_versions['ver_short'])   . "';\n";
      print "document.dialog.ver_version.value = '" . mysql_real_escape_string($a_versions['ver_version']) . "';\n";
      print "document.dialog.ver_year.value = '"    . mysql_real_escape_string($a_versions['ver_year'])    . "';\n";

      if ($a_versions['ver_core']) {
        print "document.dialog.ver_core.checked = true;\n";
      } else {
        print "document.dialog.ver_core.checked = false;\n";
      }
      if ($a_versions['ver_active']) {
        print "document.dialog.ver_active.checked = true;\n";
      } else {
        print "document.dialog.ver_active.checked = false;\n";
      }
      if ($a_versions['ver_admin']) {
        print "document.dialog.ver_admin.checked = true;\n";
      } else {
        print "document.dialog.ver_admin.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
