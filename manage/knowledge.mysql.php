<?php
# Script: knowledge.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "knowledge.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#knowledge\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Knowledge Skill Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('knowledge-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"knowledge-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>";
  $output .= "  <li><strong>Knowledge</strong> - .</li>";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";

# this has a knowledge and a link to an attribute.
# get the list of knowledge the character knows about
# print name, type, attribute, stat, rank, dice pool


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Knowledge Type</th>\n";
  $output .= "  <th class=\"ui-state-default\">Knowledge Skill</th>\n";
  $output .= "  <th class=\"ui-state-default\">Skill Rank</th>\n";
  $output .= "  <th class=\"ui-state-default\">Associated Attribute</th>\n";
  $output .= "  <th class=\"ui-state-default\">Attribute Score</th>\n";
  $output .= "  <th class=\"ui-state-default\">Dice Pool</th>\n";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select know_name,know_attribute,r_know_rank,r_know_specialize ";
  $q_string .= "from r_knowledge ";
  $q_string .= "left join knowledge on knowledge.know_id = r_knowledge.r_know_number ";
  $q_string .= "where r_know_character = " . $formVars['id'] . " ";
  $q_string .= "order by know_name ";
  $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_knowledge) > 0) {
    while ($a_r_knowledge = mysql_fetch_array($q_r_knowledge)) {

      $q_string  = "select s_know_name,s_know_attribute,ver_book,s_know_page ";
      $q_string .= "from s_knowledge ";
      $q_string .= "left join versions on versions.ver_id = s_knowledge.s_know_book ";
      $q_string .= "where s_know_id = " . $a_r_knowledge['know_attribute'];
      $q_s_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_s_knowledge = mysql_fetch_array($q_s_knowledge);

      $q_string  = "select att_name,att_column ";
      $q_string .= "from attributes ";
      $q_string .= "where att_id = " . $a_s_knowledge['s_know_attribute'] . " ";
      $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_attributes = mysql_fetch_array($q_attributes);

      $q_string  = "select " . $a_attributes['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_runners = mysql_fetch_array($q_runners);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_s_knowledge['s_know_name']                                             . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_knowledge['know_name']                                               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_knowledge['r_know_rank']                                             . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_attributes['att_name']                                                 . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners[$a_attributes['att_column']]                                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_attributes['att_column']]) . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_s_knowledge['ver_book'] . ": " . $a_s_knowledge['s_know_page']         . "</td>\n";
      $output .= "</tr>\n";

      if (strlen($a_r_knowledge['r_know_specialize']) > 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . $a_r_knowledge['know_name']                                                   . "</td>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . "&gt; " . $a_r_knowledge['r_know_specialize']                                 . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . ($a_r_knowledge['r_know_rank'] + 2)                                           . "</td>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . $a_attributes['att_name']                                                     . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners[$a_attributes['att_column']]                                       . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_attributes['att_column']] + 2) . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $a_s_knowledge['ver_book'] . ": " . $a_s_knowledge['s_know_page']             . "</td>\n";
        $output .= "</tr>\n";
      }
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"7\">No Knowledge Skills found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>\n";
?>

document.getElementById('knowledge_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

