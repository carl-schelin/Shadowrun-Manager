<?php
# Script: commlink.fill.php
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
    $package = "commlink.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_commlink");

      $q_string  = "select link_brand,link_model ";
      $q_string .= "from r_commlink ";
      $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
      $q_string .= "where r_link_id = " . $formVars['id'];
      $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_commlink = mysqli_fetch_array($q_r_commlink);
      mysql_free_result($q_r_commlink);

      print "document.getElementById('r_link_item').innerHTML = '" . mysql_real_escape_string($a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model']) . "';\n\n";

      print "document.edit.r_link_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_link_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
