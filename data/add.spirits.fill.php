<?php
# Script: add.spirits.fill.php
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
    $package = "add.spirits.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from spirits");

      $q_string  = "select spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
      $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,spirit_edge,";
      $q_string .= "spirit_essence,spirit_magic,spirit_book,spirit_page ";
      $q_string .= "from spirits ";
      $q_string .= "where spirit_id = " . $formVars['id'];
      $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_spirits = mysqli_fetch_array($q_spirits);
      mysqli_free_result($q_spirits);

      print "document.dialog.spirit_name.value = '"        . mysqli_real_escape_string($db, $a_spirits['spirit_name'])        . "';\n";
      print "document.dialog.spirit_body.value = '"        . mysqli_real_escape_string($db, $a_spirits['spirit_body'])        . "';\n";
      print "document.dialog.spirit_agility.value = '"     . mysqli_real_escape_string($db, $a_spirits['spirit_agility'])     . "';\n";
      print "document.dialog.spirit_reaction.value = '"    . mysqli_real_escape_string($db, $a_spirits['spirit_reaction'])    . "';\n";
      print "document.dialog.spirit_strength.value = '"    . mysqli_real_escape_string($db, $a_spirits['spirit_strength'])    . "';\n";
      print "document.dialog.spirit_willpower.value = '"   . mysqli_real_escape_string($db, $a_spirits['spirit_willpower'])   . "';\n";
      print "document.dialog.spirit_logic.value = '"       . mysqli_real_escape_string($db, $a_spirits['spirit_logic'])       . "';\n";
      print "document.dialog.spirit_intuition.value = '"   . mysqli_real_escape_string($db, $a_spirits['spirit_intuition'])   . "';\n";
      print "document.dialog.spirit_charisma.value = '"    . mysqli_real_escape_string($db, $a_spirits['spirit_charisma'])    . "';\n";
      print "document.dialog.spirit_edge.value = '"        . mysqli_real_escape_string($db, $a_spirits['spirit_edge'])        . "';\n";
      print "document.dialog.spirit_essence.value = '"     . mysqli_real_escape_string($db, $a_spirits['spirit_essence'])     . "';\n";
      print "document.dialog.spirit_magic.value = '"       . mysqli_real_escape_string($db, $a_spirits['spirit_magic'])       . "';\n";
      print "document.dialog.spirit_book.value = '"        . mysqli_real_escape_string($db, $a_spirits['spirit_book'])        . "';\n";
      print "document.dialog.spirit_page.value = '"        . mysqli_real_escape_string($db, $a_spirits['spirit_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
