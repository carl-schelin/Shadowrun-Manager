<?php
# Script: alchemy.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "alchemy.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Alchemical Preparations</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Group</th>\n";
  $output .= "  <th class=\"ui-state-default\">Class</th>\n";
  $output .= "  <th class=\"ui-state-default\">Spell</th>\n";
  $output .= "  <th class=\"ui-state-default\">Type</th>\n";
  $output .= "  <th class=\"ui-state-default\">Test</th>\n";
  $output .= "  <th class=\"ui-state-default\">Range</th>\n";
  $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
  $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
  $output .= "  <th class=\"ui-state-default\">Drain</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_alc_special,class_name,spell_name,spell_class,spell_group,spell_type,spell_test,spell_range,";
  $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
  $q_string .= "from r_alchemy ";
  $q_string .= "left join spells on spells.spell_id = r_alchemy.r_alc_number ";
  $q_string .= "left join class on class.class_id = spells.spell_group ";
  $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
  $q_string .= "where r_alc_character = " . $formVars['id'] . " ";
  $q_string .= "order by spell_group,spell_name ";
  $q_r_alchemy = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_alchemy) > 0) {
    while ($a_r_alchemy = mysqli_fetch_array($q_r_alchemy)) {

      if (strlen($a_r_alchemy['r_alc_special']) > 0) {
        $special = " (" . $a_r_alchemy['r_alc_special'] . ")";
      } else {
        $special = '';
      }

      $spell_drain = return_Drain($a_r_alchemy['spell_drain'], $a_r_alchemy['spell_force']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['class_name']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_alchemy['spell_name']     . $special . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_class']               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_type']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_test']                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_range']               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_damage']              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_alchemy['spell_duration']            . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $spell_drain                              . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('alchemy_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
