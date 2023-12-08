<?php
# Script: adept.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "adept.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Adept Powers or Other Abilities</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Power Name</th>\n";
  $output .= "  <th class=\"ui-state-default\">Description</th>\n";
  $output .= "  <th class=\"ui-state-default\">Power Level</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_adp_id,r_adp_number,r_adp_level,r_adp_specialize,adp_name,adp_desc,adp_power,adp_level ";
  $q_string .= "from r_adept ";
  $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
  $q_string .= "where r_adp_character = " . $formVars['id'] . " ";
  $q_string .= "order by adp_name ";
  $q_r_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_adept) > 0) {
    while ($a_r_adept = mysqli_fetch_array($q_r_adept)) {

      if (strlen($a_r_adept['r_adp_specialize']) > 0) {
        $specialize = " (" . $a_r_adept['r_adp_specialize'] . ")";
      } else {
        $specialize = "";
      }

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_name'] . $specialize                   . "</td>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_desc']                                 . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_adept['r_adp_level']                              . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('adept_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
