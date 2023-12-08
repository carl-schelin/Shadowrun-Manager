<?php
# Script: bioware.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "bioware.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#bioware\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Bioware Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('bioware-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"bioware-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Bioware</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Essence</th>";
  $output .= "  <th class=\"ui-state-default\">Availability</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $totalcost = 0;
  $q_string  = "select r_bio_id,class_name,bio_class,bio_name,r_bio_specialize,bio_rating,bio_essence,bio_avail,";
  $q_string .= "bio_perm,ver_book,bio_page,bio_cost,grade_name,grade_essence ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join class on class.class_id = bioware.bio_class ";
  $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
  $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
  $q_string .= "where r_bio_character = " . $formVars['id'] . " ";
  $q_string .= "order by bio_class,bio_name,bio_rating,ver_version ";
  $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_bioware) > 0) {
    while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

      $bio_name = $a_r_bioware['bio_name'];
      if ($a_r_bioware['r_bio_specialize'] != '') {
        $bio_name = $a_r_bioware['bio_name'] . " (" . $a_r_bioware['r_bio_specialize'] . ")";
      }

      $grade = '';
      if ($a_r_bioware['grade_essence'] != 1.00) {
        $grade = " (" . $a_r_bioware['grade_name'] . ")";
      }

      $rating = return_Rating($a_r_bioware['bio_rating']);

      $essence = return_Essence($a_r_bioware['bio_essence']);

      $totalcost += $a_r_bioware['bio_cost'];
      $cost = return_Cost($a_r_bioware['bio_cost']);

      $book = return_Book($a_r_bioware['ver_book'], $a_r_bioware['bio_page']);

      $avail = return_Avail($a_r_bioware['bio_avail'], $a_r_bioware['bio_perm']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_bioware['class_name'] . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $bio_name . $grade         . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $essence                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                      . "</td>";
      $output .= "</tr>";

    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"7\">No Bioware selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('bioware_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
