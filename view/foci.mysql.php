<?php
# Script: foci.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "foci.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output = '';

  $q_string  = "select r_foci_id,foci_name,foci_karma ";
  $q_string .= "from r_foci ";
  $q_string .= "left join foci on foci.foci_id = r_foci.r_foci_number ";
  $q_string .= "where r_foci_character = " . $formVars['id'] . " ";
  $q_string .= "order by foci_name ";
  $q_r_foci = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_foci) > 0) {

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
    $output .= "<tr>";
    $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Power Foci</th>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "  <th class=\"ui-state-default\">Name</th>";
    $output .= "  <th class=\"ui-state-default\">Karma</th>";
    $output .= "</tr>";

    while ($a_r_foci = mysqli_fetch_array($q_r_foci)) {

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_foci['foci_name']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_foci['foci_karma'] . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>";
  } else {
    $output  = "";
  }

  print "document.getElementById('foci_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
