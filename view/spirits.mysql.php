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

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"15\">Spirits</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Spirit Name</th>";
  $output .= "  <th class=\"ui-state-default\">Force</th>";
  $output .= "  <th class=\"ui-state-default\">Services</th>";
  $output .= "  <th class=\"ui-state-default\">Bound</th>";
  $output .=   "<th class=\"ui-state-default\">Body</th>\n";
  $output .=   "<th class=\"ui-state-default\">Agility</th>\n";
  $output .=   "<th class=\"ui-state-default\">Reaction</th>\n";
  $output .=   "<th class=\"ui-state-default\">Strength</th>\n";
  $output .=   "<th class=\"ui-state-default\">Willpower</th>\n";
  $output .=   "<th class=\"ui-state-default\">Logic</th>\n";
  $output .=   "<th class=\"ui-state-default\">Intuition</th>\n";
  $output .=   "<th class=\"ui-state-default\">Charisma</th>\n";
  $output .=   "<th class=\"ui-state-default\">Edge</th>\n";
  $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
  $output .=   "<th class=\"ui-state-default\">Magic</th>\n";
  $output .= "</tr>";

  $q_string  = "select r_spirit_id,r_spirit_force,r_spirit_services,r_spirit_bound,";
  $q_string .= "spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
  $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,";
  $q_string .= "spirit_edge,spirit_essence,spirit_magic ";
  $q_string .= "from r_spirit ";
  $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
  $q_string .= "where r_spirit_character = " . $formVars['id'] . " ";
  $q_string .= "order by spirit_name ";

  $q_r_spirit = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_spirit) > 0) {
    while ($a_r_spirit = mysqli_fetch_array($q_r_spirit)) {

      $spirit_body      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_body']);
      $spirit_agility   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_agility']);
      $spirit_reaction  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_reaction']);
      $spirit_strength  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_strength']);
      $spirit_willpower = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_willpower']);
      $spirit_logic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_logic']);
      $spirit_intuition = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_intuition']);
      $spirit_charisma  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_charisma']);
      $spirit_edge      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_edge']);
      $spirit_essence   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_essence']);
      $spirit_magic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_magic']);

      $bound = 'No';
      if ($a_r_spirit['spirit_bound']) {
        $bound = 'Yes';
      }

      $output .= "<tr>";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_spirit['spirit_name']     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_force']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spirit['spirit_services'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $bound                         . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_body                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_agility                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_reaction               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_strength               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_willpower              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_logic                  . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_intuition              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_charisma               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_edge                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_essence                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spirit_magic                  . "</td>\n";
      $output .= "</tr>";

    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('spirits_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
