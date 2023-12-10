<?php
# Script: add.program.fill.php
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
    $package = "add.program.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from program");

      $q_string  = "select pgm_name,pgm_type,pgm_desc,pgm_avail,pgm_perm,pgm_cost,pgm_book,pgm_page ";
      $q_string .= "from program ";
      $q_string .= "where pgm_id = " . $formVars['id'];
      $q_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_program = mysqli_fetch_array($q_program);
      mysqli_free_result($q_program);

      print "document.dialog.pgm_name.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_name'])  . "';\n";
      print "document.dialog.pgm_type.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_type'])  . "';\n";
      print "document.dialog.pgm_desc.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_desc'])  . "';\n";
      print "document.dialog.pgm_avail.value = '" . mysqli_real_escape_string($db, $a_program['pgm_avail']) . "';\n";
      print "document.dialog.pgm_perm.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_perm'])  . "';\n";
      print "document.dialog.pgm_cost.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_cost'])  . "';\n";
      print "document.dialog.pgm_book.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_book'])  . "';\n";
      print "document.dialog.pgm_page.value = '"  . mysqli_real_escape_string($db, $a_program['pgm_page'])  . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
