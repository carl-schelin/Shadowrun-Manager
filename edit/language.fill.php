<?php
# Script: language.fill.php
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
    $package = "language.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_language");

      $q_string  = "select r_lang_number,r_lang_rank,r_lang_specialize,r_lang_expert ";
      $q_string .= "from r_language ";
      $q_string .= "where r_lang_id = " . $formVars['id'];
      $q_r_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_language = mysqli_fetch_array($q_r_language);
      mysql_free_result($q_r_language);

      print "document.edit.r_lang_number.value = '"     . mysql_real_escape_string($a_r_language['r_lang_number'])     . "';\n";
      print "document.edit.r_lang_rank.value = '"       . mysql_real_escape_string($a_r_language['r_lang_rank'])       . "';\n";
      print "document.edit.r_lang_specialize.value = '" . mysql_real_escape_string($a_r_language['r_lang_specialize']) . "';\n";

      if ($a_r_language['r_lang_expert']) {
        print "document.edit.r_lang_expert.checked = true;\n";
      } else {
        print "document.edit.r_lang_expert.checked = false;\n";
      }

      print "document.edit.r_lang_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_lang_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
