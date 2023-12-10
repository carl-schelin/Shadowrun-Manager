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

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#qualities\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Qualities Information";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('qualities-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"qualities-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>";
  $output .= "  <li><strong>Product</strong> - The product this software is a member of.</li>";
  $output .= "  <li><strong>Vendor</strong> - The software vendor. Clicking on this will load a search page listing all systems associated with this vendor.</li>";
  $output .= "  <li><strong>Software</strong> - The software name and version. Clicking on this will load a search page listing all systems associated with this software package.</li>";
  $output .= "  <li><strong>Type</strong> - The type of software.  Clicking on this will load a search page listing all systems associated with this software type. This is used in various places for reporting (such as OS and Instance).</li>";
  $output .= "  <li><strong>Group</strong> - The group responsible for the software package.</li>";
  $output .= "  <li><strong>Updated</strong> - The last time this entry was updated. A checkmark indicates the software information was gathered automatically.</li>";
  $output .= "</ul>";

  $output .= "<p><span class=\"ui-state-highlight\">Highlighted software</span> have been identified as software that defines or is the focus of this system.</p>";

  $output .= "<p><span class=\"ui-state-error\">Highlighted software</span> have been identified as customer facing</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Positive Qualities</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Quality</th>\n";
  $output .= "  <th class=\"ui-state-default\">Value</th>\n";
  $output .= "  <th class=\"ui-state-default\">Description</th>\n";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
  $q_string .= "from r_qualities ";
  $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
  $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
  $q_string .= "where r_qual_character = " . $formVars['id'] . " and qual_value > 0 ";
  $q_string .= "order by qual_name ";
  $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_qualities) > 0) {
    while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_name']  . (strlen($a_r_qualities['r_qual_details']) ? " (" . $a_r_qualities['r_qual_details'] . ")" : '') . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['qual_value']                                                                                                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_desc']                                                                                                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['ver_book']  . ": " . $a_r_qualities['qual_page']                                                               . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"4\">No Positive Qualities found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>\n";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Negative Qualities</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Quality</th>\n";
  $output .= "  <th class=\"ui-state-default\">Value</th>\n";
  $output .= "  <th class=\"ui-state-default\">Description</th>\n";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
  $q_string .= "from r_qualities ";
  $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
  $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
  $q_string .= "where r_qual_character = " . $formVars['id'] . " and qual_value < 0 ";
  $q_string .= "order by qual_name ";
  $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_qualities) > 0) {
    while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

      if (strlen($a_r_qualities['r_qual_details']) > 0) {
        $details = " (" . $a_r_qualities['r_qual_details'] . ")";
      } else {
        $details = '';
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_name']                          . $details . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['qual_value']                                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_desc']                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['ver_book']  . ": " . $a_r_qualities['qual_page'] . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"4\">No Negative Qualities found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>\n";

?>

document.getElementById('qualities_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

