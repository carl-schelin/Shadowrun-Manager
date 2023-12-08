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

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Knowledge Skills</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Knowledge Type</th>\n";
  $output .= "  <th class=\"ui-state-default\">Knowledge Skill</th>\n";
  $output .= "  <th class=\"ui-state-default\">Dice Pool</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select know_name,know_attribute,r_know_rank,r_know_specialize ";
  $q_string .= "from r_knowledge ";
  $q_string .= "left join knowledge on knowledge.know_id = r_knowledge.r_know_number ";
  $q_string .= "where r_know_character = " . $formVars['id'] . " ";
  $q_string .= "order by know_name ";
  $q_r_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_knowledge) > 0) {
    while ($a_r_knowledge = mysqli_fetch_array($q_r_knowledge)) {

      $q_string  = "select s_know_name,s_know_attribute,ver_book,s_know_page ";
      $q_string .= "from s_knowledge ";
      $q_string .= "left join versions on versions.ver_id = s_knowledge.s_know_book ";
      $q_string .= "where s_know_id = " . $a_r_knowledge['know_attribute'];
      $q_s_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_s_knowledge = mysqli_fetch_array($q_s_knowledge);

      $q_string  = "select att_name,att_column ";
      $q_string .= "from attributes ";
      $q_string .= "where att_id = " . $a_s_knowledge['s_know_attribute'] . " ";
      $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_attributes = mysqli_fetch_array($q_attributes);

      $q_string  = "select " . $a_attributes['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_runners = mysqli_fetch_array($q_runners);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_s_knowledge['s_know_name']                                             . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_knowledge['know_name']                                               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_attributes['att_column']]) . "</td>\n";
      $output .= "</tr>\n";

      if (strlen($a_r_knowledge['r_know_specialize']) > 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . "&nbsp;"                                                                      . "</td>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . "&gt; " . $a_r_knowledge['r_know_specialize']                                 . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_attributes['att_column']] + 2) . "</td>\n";
        $output .= "</tr>\n";
      }
    }
  } else {
    $output = "";
  }

  $output .= "</table>\n";

  print "document.getElementById('knowledge_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
