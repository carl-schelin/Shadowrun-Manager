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

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#spells\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Spell Information";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spells-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"spells-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Drain</strong> - Roll Tradition Drain - if Net Hits > Magic, damage is physical, otherwise stun.</li>\n";
  $output .= "  <li><strong>Combat Spells</strong> sr5/283\n";
  $output .= "    <ul>\n";
  $output .= "      <li><strong>Direct</strong> - Opposed: Spellcasting + Magic (up to 2 x Magic) [Force] vs Body (Physical) or Willpower (Mana) + Counterspelling. Armor ineffective.</li>\n";
  $output .= "      <li><strong>Indirect</strong> - Spellcasting + Magic vs Reaction + Intuition + Counterspelling. Damage is Force + Net Hits, if opposed reduces to zero, no damage. Opposed soak is Body + Armor + AP (-Force).</li>\n";
  $output .= "      <li><strong>Elemental</strong> - Additional effects based on type.</li>\n";
  $output .= "    </li></ul>\n";
  $output .= "  <li><strong>Detection Spells</strong> Range = Touch. sr5/285\n";
  $output .= "    <ul>\n";
  $output .= "      <li><strong>Active</strong> - Opposed: Spellcasting + Magic [Force] vs Willpower + Logic + Counterspelling [Mental] (Physical: Living w/Aura), Force x 2 (Magical Objects), or Object Resistance (sr5/295).</li>\n";
  $output .= "      <li><strong>Area</strong> - Like hearing. Force x Magic x 10 in meters</li>\n";
  $output .= "      <li><strong>Directional</strong> - Like normal sight.</li>\n";
  $output .= "      <li><strong>Extended Area</strong> - Like hearing. Force x Magic x 10 in meters.</li>\n";
  $output .= "      <li><strong>Passive</strong> - Spellcasting + Magic [Force]. Net Hits replaces Mental as the detection [Limit].</li>\n";
  $output .= "      <li><strong>Psychic</strong> - Adds an extra sense like Telepathy.</li>\n";
  $output .= "    </li></ul>\n";
  $output .= "  <li><strong>Health Spells</strong> Healing is -points of lost essence rounded down to dice pool. sr5/287";
  $output .= "    <ul>\n";
  $output .= "      <li><strong>Negative</strong> - Opposed: Spellcasting + Magic vs Attribute + Counterspelling.</li>\n";
  $output .= "    </li></ul>\n";
  $output .= "  <li><strong>Illusion Spells</strong> sr5/289\n";
  $output .= "    <ul>\n";
  $output .= "      <li><strong>Area</strong> - .</li>\n";
  $output .= "      <li><strong>Multi-Sense</strong> - .</li>\n";
  $output .= "      <li><strong>Obvious</strong> - .</li>\n";
  $output .= "      <li><strong>Realistic</strong> - .</li>\n";
  $output .= "      <li><strong>Single-Sense</strong> - .</li>\n";
  $output .= "    </li></ul>\n";
  $output .= "  <li><strong>Manipulation Spells</strong> sr5/292\n";
  $output .= "    <ul>\n";
  $output .= "      <li><strong>Area</strong> - .</li>\n";
  $output .= "      <li><strong>Environmental</strong> - .</li>\n";
  $output .= "      <li><strong>Mental</strong> - .</li>\n";
  $output .= "      <li><strong>Physical</strong> - .</li>\n";
  $output .= "    </li></ul>\n";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
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
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_spell_special,spell_name,class_name,spell_class,spell_group,spell_type,spell_test,spell_range,";
  $q_string .= "spell_damage,spell_duration,spell_drain,ver_book,spell_page ";
  $q_string .= "from r_spells ";
  $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
  $q_string .= "left join class on class.class_id = spells.spell_group ";
  $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
  $q_string .= "where r_spell_character = " . $formVars['id'] . " ";
  $q_string .= "order by class_name,spell_name ";
  $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_spells) > 0) {
    while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

      if (strlen($a_r_spells['r_spell_special']) > 0) {
        $special = " (" . $a_r_spells['r_spell_special'] . ")";
      } else {
        $special = '';
      }

      $spell_drain = return_Drain($a_r_spells['spell_drain']);

      $spell_book = return_Book($a_r_spells['ver_book'], $a_r_spells['spell_page']);

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
      $output .= "  <td class=\"ui-widget-content delete\">" . $spell_book                              . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">" . "No Spells added" . "</td>\n";
    $output .= "</tr>\n";
  }

  $output .= "</table>\n";
?>

document.getElementById('spells_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

