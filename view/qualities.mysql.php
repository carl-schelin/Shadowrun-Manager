<?php
# Script: qualities.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "qualities.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Qualities</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Quality</th>\n";
  $output .= "  <th class=\"ui-state-default\">Description</th>\n";
  $output .= "  <th class=\"ui-state-default\">Type</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
  $q_string .= "from r_qualities ";
  $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
  $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
  $q_string .= "where r_qual_character = " . $formVars['id'] . " ";
  $q_string .= "order by qual_value desc,qual_name ";
  $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_qualities) > 0) {
    while ($a_r_qualities = mysql_fetch_array($q_r_qualities)) {

      if (strlen($a_r_qualities['r_qual_details']) > 0) {
        $details = " (" . $a_r_qualities['r_qual_details'] . ")";
      } else {
        $details = '';
      }

      if ($a_r_qualities['qual_value'] < 0) {
        $quality = "Negative";
      } else {
        $quality = "Positive";
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">" . $a_r_qualities['qual_name'] . $details . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">" . $a_r_qualities['qual_desc']            . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">" . $quality                               . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output = "";
  }

  $output .= "</table>\n";

  print "document.getElementById('qualities_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
