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

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Bioware</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Bioware</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Essence</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_bio_id,r_bio_specialize,class_name,bio_name,bio_rating,bio_essence,grade_name,grade_essence ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
  $q_string .= "left join class on class.class_id = bioware.bio_class ";
  $q_string .= "where r_bio_character = " . $formVars['id'] . " ";
  $q_string .= "order by bio_class,bio_name,bio_rating ";
  $q_r_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
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

      $essencegrade = ($a_r_bioware['bio_essence'] * $a_r_bioware['grade_essence']);
      $essence = return_Essence($essencegrade);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_bioware['class_name'] . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $bio_name . $grade         . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $essence                   . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('bioware_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
