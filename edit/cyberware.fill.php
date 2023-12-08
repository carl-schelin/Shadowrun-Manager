<?php
# Script: cyberware.fill.php
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
    $package = "cyberware.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_cyberware");

      $q_string  = "select r_ware_specialize,r_ware_grade,r_ware_number,ware_name,ware_rating,ware_essence,ware_capacity ";
      $q_string .= "from r_cyberware ";
      $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
      $q_string .= "where r_ware_id = " . $formVars['id'];
      $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_cyberware = mysqli_fetch_array($q_r_cyberware);
      mysql_free_result($q_r_cyberware);

      $rating = return_Rating($a_r_cyberware['ware_rating']);

      $essence = return_Essence($a_r_cyberware['ware_essence']);

      $capacity = return_Capacity($a_r_cyberware['ware_capacity']);

      $ware_name = $a_r_cyberware['ware_name'] . " [Rating: " . $rating . ", Essence: " . $essence . ", Capacity: " . $capacity . "]";

      print "document.getElementById('r_ware_item').innerHTML = '" . mysql_real_escape_string($ware_name) . "';\n\n";
      print "document.edit.r_ware_number.value = '" . mysql_real_escape_string($a_r_cyberware['r_ware_number']) . "';\n\n";
      print "document.edit.r_ware_specialize.value = '" . mysql_real_escape_string($a_r_cyberware['r_ware_specialize']) . "';\n\n";
      print "document.edit.r_ware_grade.value = '" . mysql_real_escape_string($a_r_cyberware['r_ware_grade']) . "';\n\n";

      print "document.edit.r_ware_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_ware_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
