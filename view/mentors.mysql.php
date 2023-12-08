<?php
# Script: mentors.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "mentors.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Mentor Spirits</th>";
  $output .= "</tr>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Mentor Spirit</th>";
  $output .= "  <th class=\"ui-state-default\">Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Mage Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Adept Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Disadvantages</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage ";
  $q_string .= "from r_mentor ";
  $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
  $q_string .= "where r_mentor_character = " . $formVars['id'] . " ";
  $q_string .= "order by mentor_name ";
  $q_r_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_mentor) > 0) {
    while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_mentor['mentor_name']                                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_all']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_mage']                                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_adept']                                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_disadvantage']                          . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>";
  } else {
    $output = "";
  }

  print "document.getElementById('mentors_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
