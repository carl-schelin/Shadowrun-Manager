<?php
# Script: spirits.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "spirits.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#spirits\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Spirit Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spirit-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"spirit-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Spirit</th>";
  $output .= "  <th class=\"ui-state-default\">Force</th>";
  $output .= "  <th class=\"ui-state-default\">Services</th>";
  $output .= "  <th class=\"ui-state-default\">Bound</th>";
  $output .= "  <th class=\"ui-state-default\">Body</th>";
  $output .= "  <th class=\"ui-state-default\">Agility</th>";
  $output .= "  <th class=\"ui-state-default\">Reaction</th>";
  $output .= "  <th class=\"ui-state-default\">Strength</th>";
  $output .= "  <th class=\"ui-state-default\">Willpower</th>";
  $output .= "  <th class=\"ui-state-default\">Logic</th>";
  $output .= "  <th class=\"ui-state-default\">Intuition</th>";
  $output .= "  <th class=\"ui-state-default\">Charisma</th>";
  $output .= "  <th class=\"ui-state-default\">Edge</th>";
  $output .= "  <th class=\"ui-state-default\">Essence</th>";
  $output .= "  <th class=\"ui-state-default\">Magic</th>";
  $output .= "</tr>";

  $q_string  = "select r_spirit_id,spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
  $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,spirit_edge,spirit_essence,";
  $q_string .= "spirit_magic,r_spirit_force,r_spirit_services,r_spirit_bound ";
  $q_string .= "from r_spirit ";
  $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
  $q_string .= "where r_spirit_character = " . $formVars['id'] . " ";
  $q_r_spirit = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_spirit) > 0) {
    while ($a_r_spirit = mysqli_fetch_array($q_r_spirit)) {

      $bound = 'No';
      if ($a_r_spirit['r_spirit_bound']) {
        $bound = 'Yes';
      }

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_spirit['spirit_name']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['r_spirit_force']    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['r_spirit_services'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $bound                           . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_body']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_agility']    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_reaction']   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_strength']   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_willpower']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_logic']      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_intuition']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_charisma']   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_edge']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_essence']    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_magic']      . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"15\">No Spirits found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('spirits_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
