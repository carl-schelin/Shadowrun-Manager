<?php
# Script: identity.fill.php
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
    $package = "identity.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_identity");

      $q_string  = "select id_name,id_type,id_rating ";
      $q_string .= "from r_identity ";
      $q_string .= "where id_id = " . $formVars['id'];
      $q_r_identity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_identity = mysql_fetch_array($q_r_identity);
      mysql_free_result($q_r_identity);

      print "document.edit.id_name.value = '"   . mysql_real_escape_string($a_r_identity['id_name'])   . "';\n";
      print "document.edit.id_rating.value = '" . mysql_real_escape_string($a_r_identity['id_rating']) . "';\n";

      print "document.edit.id_type['" . $a_r_identity['id_type'] . "'].checked = true;\n";

      print "document.edit.id_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.id_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
