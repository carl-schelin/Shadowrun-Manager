<?php
# Script: add.spritepower.fill.php
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
    $package = "add.spritepower.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from sprite_powers");

      $q_string  = "select pow_name,pow_description,pow_book,pow_page ";
      $q_string .= "from sprite_powers ";
      $q_string .= "where pow_id = " . $formVars['id'];
      $q_sprite_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_sprite_powers = mysqli_fetch_array($q_sprite_powers);
      mysql_free_result($q_sprite_powers);

      print "document.dialog.pow_name.value = '"         . mysql_real_escape_string($a_sprite_powers['pow_name'])        . "';\n";
      print "document.dialog.pow_description.value = '"  . mysql_real_escape_string($a_sprite_powers['pow_description']) . "';\n";
      print "document.dialog.pow_book.value = '"         . mysql_real_escape_string($a_sprite_powers['pow_book'])        . "';\n";
      print "document.dialog.pow_page.value = '"         . mysql_real_escape_string($a_sprite_powers['pow_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
