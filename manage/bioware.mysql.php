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

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
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

  $nuyen = '&yen;';
  $q_string  = "select r_bio_id,bio_class,bio_name,bio_rating,bio_essence,bio_avail,bio_perm,ver_book,bio_page,bio_cost ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
  $q_string .= "where r_bio_character = " . $formVars['id'] . " ";
  $q_string .= "order by bio_class,bio_name,bio_rating,ver_version ";
  $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_bioware) > 0) {
    while ($a_r_bioware = mysql_fetch_array($q_r_bioware)) {

      $rating = return_Rating($a_r_bioware['bio_rating']);

      $essence = return_Essence($a_r_bioware['bio_essence']);

      $avail = return_Avail($a_r_bioware['bio_avail'], $a_r_bioware['bio_perm']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_bioware['bio_class']                                     . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_bioware['bio_name']                                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                                                       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $essence                                                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                                                        . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . number_format($a_r_bioware['bio_cost'], 0, '.', ',') . $nuyen . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_bioware['ver_book'] . ": " . $a_r_bioware['bio_page']    . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"7\">No Bioware selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('bioware_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
