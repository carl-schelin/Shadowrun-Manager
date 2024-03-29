<?php
# Script: add.sprites.fill.php
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
    $package = "add.sprites.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from sprites");

      $q_string  = "select sprite_name,sprite_attack,sprite_sleaze,sprite_data,";
      $q_string .= "sprite_firewall,sprite_initiative,sprite_book,sprite_page ";
      $q_string .= "from sprites ";
      $q_string .= "where sprite_id = " . $formVars['id'];
      $q_sprites = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_sprites = mysqli_fetch_array($q_sprites);
      mysqli_free_result($q_sprites);

      print "document.dialog.sprite_name.value = '"       . mysqli_real_escape_string($db, $a_sprites['sprite_name'])        . "';\n";
      print "document.dialog.sprite_attack.value = '"     . mysqli_real_escape_string($db, $a_sprites['sprite_attack'])      . "';\n";
      print "document.dialog.sprite_sleaze.value = '"     . mysqli_real_escape_string($db, $a_sprites['sprite_sleaze'])      . "';\n";
      print "document.dialog.sprite_data.value = '"       . mysqli_real_escape_string($db, $a_sprites['sprite_data'])        . "';\n";
      print "document.dialog.sprite_firewall.value = '"   . mysqli_real_escape_string($db, $a_sprites['sprite_firewall'])    . "';\n";
      print "document.dialog.sprite_initiative.value = '" . mysqli_real_escape_string($db, $a_sprites['sprite_initiative'])  . "';\n";
      print "document.dialog.sprite_book.value = '"       . mysqli_real_escape_string($db, $a_sprites['sprite_book'])        . "';\n";
      print "document.dialog.sprite_page.value = '"       . mysqli_real_escape_string($db, $a_sprites['sprite_page'])        . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
