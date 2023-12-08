<?php
# Script: contact.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "contact.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_contact");

      $q_string  = "select r_con_number,r_con_loyalty,r_con_connection,r_con_faction,r_con_notes ";
      $q_string .= "from r_contact ";
      $q_string .= "where r_con_id = " . $formVars['id'];
      $q_r_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_contact = mysqli_fetch_array($q_r_contact);
      mysql_free_result($q_r_contact);

      print "document.edit.r_con_number.value = '"     . mysql_real_escape_string($a_r_contact['r_con_number'])     . "';\n";
      print "document.edit.r_con_loyalty.value = '"    . mysql_real_escape_string($a_r_contact['r_con_loyalty'])    . "';\n";
      print "document.edit.r_con_connection.value = '" . mysql_real_escape_string($a_r_contact['r_con_connection']) . "';\n";
      print "document.edit.r_con_faction.value = '"    . mysql_real_escape_string($a_r_contact['r_con_faction'])    . "';\n";
      print "document.edit.r_con_notes.value = '"      . mysql_real_escape_string($a_r_contact['r_con_notes'])      . "';\n";

      print "document.edit.r_con_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_con_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
