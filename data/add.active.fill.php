<?php
# Script: add.active.fill.php
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
    $package = "add.active.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from active");

      $q_string  = "select act_type,act_name,act_group,act_attribute,act_default,act_book,act_page ";
      $q_string .= "from active ";
      $q_string .= "where act_id = " . $formVars['id'];
      $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_active = mysqli_fetch_array($q_active);
      mysql_free_result($q_active);

      print "document.dialog.act_type.value = '"      . mysql_real_escape_string($a_active['act_type'])      . "';\n";
      print "document.dialog.act_name.value = '"      . mysql_real_escape_string($a_active['act_name'])      . "';\n";
      print "document.dialog.act_group.value = '"     . mysql_real_escape_string($a_active['act_group'])     . "';\n";
      print "document.dialog.act_attribute.value = '" . mysql_real_escape_string($a_active['act_attribute']) . "';\n";
      print "document.dialog.act_book.value = '"      . mysql_real_escape_string($a_active['act_book'])      . "';\n";
      print "document.dialog.act_page.value = '"      . mysql_real_escape_string($a_active['act_page'])      . "';\n";

      if ($a_active['act_default']) {
        print "document.dialog.act_default.checked = true;\n";
      } else {
        print "document.dialog.act_default.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
