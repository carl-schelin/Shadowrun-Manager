<?php
# Script: mentor.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "mentor.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#magic#mentor\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Mentor Spirit Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('mentor-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"mentor-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Mentor Spirit</th>";
  $output .= "  <th class=\"ui-state-default\">Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Mage Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Adept Advantages</th>";
  $output .= "  <th class=\"ui-state-default\">Disadvantages</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,ver_book,mentor_page ";
  $q_string .= "from r_mentor ";
  $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
  $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
  $q_string .= "where r_mentor_character = " . $formVars['id'] . " ";
  $q_string .= "order by mentor_name,ver_version ";
  $q_r_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_mentor) > 0) {
    while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

      $mentor_book = return_Book($a_r_mentor['ver_book'], $a_r_mentor['mentor_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_mentor['mentor_name']         . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_all']          . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_mage']         . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_adept']        . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_mentor['mentor_disadvantage'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $mentor_book                       . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"6\">No Mentor Spirit selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('mentor_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
