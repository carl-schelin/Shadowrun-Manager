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

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#adept\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Adept Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('adept-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"adept-help\" style=\"display: none\">";

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
  $output .= "  <th class=\"ui-state-default\">Power Name</th>\n";
  $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
  $output .= "  <th class=\"ui-state-default\">Level</th>\n";
  $output .= "  <th class=\"ui-state-default\">Spent</th>\n";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_adp_id,r_adp_number,r_adp_level,r_adp_specialize,adp_name,adp_desc,adp_power,adp_level,ver_book,adp_page ";
  $q_string .= "from r_adept ";
  $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
  $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
  $q_string .= "where r_adp_character = " . $formVars['id'] . " ";
  $q_string .= "order by adp_name ";
  $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_adept) > 0) {
    while ($a_r_adept = mysql_fetch_array($q_r_adept)) {

      if ($a_r_adept['adp_level'] == 0) {
        $level = "";
      } else {
        $level = " per level";
      }

      if (strlen($a_r_adept['r_adp_specialize']) > 0) {
        $specialize = " (" . $a_r_adept['r_adp_specialize'] . ")";
      } else {
        $specialize = "";
      }

      if ($a_r_adept['r_adp_level'] == 0) {
        $powerpoints = $a_r_adept['adp_power'];
      } else {
        $powerpoints = ($a_r_adept['adp_power'] * $a_r_adept['r_adp_level']);
      }

      $adept_book = return_Book($a_r_adept['ver_book'], $a_r_adept['adp_page']);

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_name'] . $specialize . "</td>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_desc']               . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_adept['adp_power'] . $level     . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_adept['r_adp_level']            . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . number_format($cost, 2, '.', ',')    . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $adept_book                          . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">" . "No Adept Powers added" . "</td>\n";
    $output .= "</tr>\n";
  }

  $output .= "</table>\n";
?>

document.getElementById('adept_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

