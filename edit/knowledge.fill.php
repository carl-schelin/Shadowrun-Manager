<?php
# Script: knowledge.fill.php
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
    $package = "knowledge.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_knowledge");

      $q_string  = "select r_know_number,r_know_rank,r_know_specialize,r_know_expert ";
      $q_string .= "from r_knowledge ";
      $q_string .= "where r_know_id = " . $formVars['id'];
      $q_r_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_knowledge = mysqli_fetch_array($q_r_knowledge);
      mysql_free_result($q_r_knowledge);

      print "document.edit.r_know_number.value = '"     . mysql_real_escape_string($a_r_knowledge['r_know_number'])     . "';\n";
      print "document.edit.r_know_rank.value = '"       . mysql_real_escape_string($a_r_knowledge['r_know_rank'])       . "';\n";
      print "document.edit.r_know_specialize.value = '" . mysql_real_escape_string($a_r_knowledge['r_know_specialize']) . "';\n";


      if ($a_r_knowledge['r_know_expert']) {
        print "document.edit.r_know_expert.checked = true;\n";
      } else {
        print "document.edit.r_know_expert.checked = false;\n";
      }

      print "document.edit.r_know_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_know_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
