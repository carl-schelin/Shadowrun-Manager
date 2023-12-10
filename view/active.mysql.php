<?php
# Script: active.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "active.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Active Skills</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Group</th>";
  $output .= "  <th class=\"ui-state-default\">Active Skill</th>";
  $output .= "  <th class=\"ui-state-default\">Rank</th>";
  $output .= "  <th class=\"ui-state-default\">Associated Attribute</th>";
  $output .= "  <th class=\"ui-state-default\">Score</th>";
  $output .= "  <th class=\"ui-state-default\">Dice Pool</th>";
  $output .= "</tr>\n";

  $q_string  = "select act_id,act_group,act_name,att_name,att_column,act_default ";
  $q_string .= "from active ";
  $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
  $q_string .= "left join versions on versions.ver_id = active.act_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by act_name ";
  $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_active = mysqli_fetch_array($q_active)) {

    $italicstart = "";
    $italicend = "";
    if ($a_active['act_default']) {
      $italicstart = "<i>";
      $italicend = "</i>";
    }
    $q_string  = "select " . $a_active['att_column'] . " ";
    $q_string .= "from runners ";
    $q_string .= "where runr_id = " . $formVars['id'] . " ";
    $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_runners = mysqli_fetch_array($q_runners);

    $q_string  = "select r_act_rank,r_act_group,r_act_specialize,r_act_expert ";
    $q_string .= "from r_active ";
    $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " ";
    $q_r_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_r_active) > 0) {
      $a_r_active = mysqli_fetch_array($q_r_active);

      $group = "";
      if ($a_r_active['r_act_group']) {
        $group = " *";
      }

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_active['act_group']                                            . $italicend . "</td>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $italicstart . $a_active['act_name'] . $group                                    . $italicend . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_r_active['r_act_rank']                                         . $italicend . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_active['att_name']                                             . $italicend . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_runners[$a_active['att_column']]                               . $italicend . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank']) . $italicend . "</td>\n";
      $output .= "</tr>\n";

      if (strlen($a_r_active['r_act_specialize']) > 0) {

        $expert = 2;
        if ($a_r_active['r_act_expert']) {
          $expert = 3;
        }

        $output .= "<tr>\n";
        $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                              . "</td>\n";
        $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_active['r_act_specialize']                                       . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $a_r_active['r_act_rank'] . " + " . $expert                           . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $a_active['att_name']                                                 . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $a_runners[$a_active['att_column']]                                   . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank'] + $expert) . "</td>\n";
        $output .= "</tr>\n";
      }

    } else {

# only display non-character selected active skills
      if ($a_active['act_default']) {
        $class = "ui-state-highlight";
      
        $output .= "<tr>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_active['act_group']                    . $italicend . "</td>\n";
        $output .= "  <td class=\"" . $class . "\">"        . $italicstart . $a_active['act_name']                     . $italicend . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . "-1"                                      . $italicend . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_active['att_name']                     . $italicend . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . $a_runners[$a_active['att_column']]       . $italicend . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $italicstart . ($a_runners[$a_active['att_column']] - 1) . $italicend . "</td>\n";
        $output .= "</tr>\n";
      }
    }
  }
  $output .= "</table>\n";

  print "document.getElementById('active_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
