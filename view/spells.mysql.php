<?php
# Script: spells.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "spells.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Spells / Preparations / Rituals / Complex Forms</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Group</th>\n";
  $output .= "  <th class=\"ui-state-default\">Spell</th>\n";
  $output .= "  <th class=\"ui-state-default\">Class</th>\n";
  $output .= "  <th class=\"ui-state-default\">Type</th>\n";
  $output .= "  <th class=\"ui-state-default\">Test</th>\n";
  $output .= "  <th class=\"ui-state-default\">Range</th>\n";
  $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
  $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
  $output .= "  <th class=\"ui-state-default\">Drain</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_spell_special,class_name,spell_name,spell_class,spell_group,spell_type,spell_test,spell_range,";
  $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
  $q_string .= "from r_spells ";
  $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
  $q_string .= "left join class on class.class_id = spells.spell_group ";
  $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
  $q_string .= "where r_spell_character = " . $formVars['id'] . " ";
  $q_string .= "order by spell_group,spell_name ";
  $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_spells) > 0) {
    while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

      if (strlen($a_r_spells['r_spell_special']) > 0) {
        $special = " (" . $a_r_spells['r_spell_special'] . ")";
      } else {
        $special = '';
      }

      $spell_drain = return_Drain($a_r_spells['spell_drain'], $a_r_spells['spell_force']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['class_name']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_spells['spell_name']     . $special . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_class']               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_type']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_test']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_range']               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_damage']              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_duration']            . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spell_drain                             . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('spells_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
