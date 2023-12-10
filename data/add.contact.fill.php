<?php
# Script: add.contact.fill.php
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
    $package = "add.contact.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from contact");

      $q_string  = "select con_name,con_archetype,con_character,con_book,con_page,con_owner ";
      $q_string .= "from contact ";
      $q_string .= "where con_id = " . $formVars['id'];
      $q_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_contact = mysqli_fetch_array($q_contact);
      mysqli_free_result($q_contact);

      print "document.dialog.con_character.value = '"  . mysqli_real_escape_string($db, $a_contact['con_character']) . "';\n";
      print "document.dialog.con_owner.value = '"      . mysqli_real_escape_string($db, $a_contact['con_owner'])     . "';\n";
      print "document.dialog.con_name.value = '"       . mysqli_real_escape_string($db, $a_contact['con_name'])      . "';\n";
      print "document.dialog.con_archetype.value = '"  . mysqli_real_escape_string($db, $a_contact['con_archetype']) . "';\n";
      print "document.dialog.con_book.value = '"       . mysqli_real_escape_string($db, $a_contact['con_book'])      . "';\n";
      print "document.dialog.con_page.value = '"       . mysqli_real_escape_string($db, $a_contact['con_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
