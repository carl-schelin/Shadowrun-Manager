<?php
# Script: detail.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "detail.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select runr_owner,runr_aliases,runr_name,runr_archetype,runr_agility,";
  $q_string .= "runr_body,runr_reaction,runr_strength,runr_charisma,runr_intuition,";
  $q_string .= "runr_logic,runr_willpower,runr_essence,runr_totaledge,runr_currentedge,";
  $q_string .= "runr_magic,runr_initiate,runr_resonance,runr_age,runr_sex,runr_height,runr_weight,";
  $q_string .= "runr_physicalcon,runr_stuncon,runr_desc,runr_sop,runr_available,meta_name,";
  $q_string .= "meta_walk,meta_run,meta_swim,runr_version,ver_version ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "left join versions on versions.ver_id = runners.runr_version ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

  $q_string  = "select r_deck_data ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " and r_deck_active = 1 ";
  $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_cyberdeck) > 0) {
    $a_r_cyberdeck = mysql_fetch_array($q_r_cyberdeck);
    $data_processing = $a_r_cyberdeck['r_deck_data'];
  } else {
    $data_processing = 0;
  }

  $currentkarma = 0;
  $totalkarma = 0;
  $q_string  = "select kar_karma ";
  $q_string .= "from karma ";
  $q_string .= "where kar_character = " . $formVars['id'] . " ";
  $q_karma = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_karma) > 0) {
    while ($a_karma = mysql_fetch_array($q_karma)) {
      if ($a_karma['kar_karma'] > 0) {
        $totalkarma += $a_karma['kar_karma'];
      }
      $currentkarma += $a_karma['kar_karma'];
    }
  }

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Runner Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('detail-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"detail-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "</div>";

  $output .= "</div>";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Personal Data</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Name</strong>: " . $a_runners['runr_name'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Alias</strong>: " . $a_runners['runr_aliases'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Archetype</strong>: " . $a_runners['runr_archetype'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Metatype</strong>: " . $a_runners['meta_name'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Total Karma</strong>: " . $totalkarma . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Total Edge</strong>: "     . $a_runners['runr_currentedge'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Weight</strong>: " . $a_runners['runr_weight'] . " lb/" . return_Kilograms($a_runners['runr_weight']) . " kg</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Height</strong>: " . return_Feet($a_runners['runr_height']) . "/" . return_Meters(return_Centimeters($a_runners['runr_height'])) . "m</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Sex</strong>: " . ($a_runners['runr_sex'] == 0 ? 'Female' : 'Male') . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Age</strong>: " . $a_runners['runr_age'] . " Years</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Current Karma</strong>: " . $currentkarma . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Current Edge</strong>: "     . $a_runners['runr_currentedge'] . "</td>\n";
  $output .= "</tr>";
  $output .= "</table>";



# first edition
  if ($a_runners['runr_version'] == 21) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# second edition
  if ($a_runners['runr_version'] == 23) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# third edition FASA
  if ($a_runners['runr_version'] == 24) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# third edition FanPro
  if ($a_runners['runr_version'] == 40) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# fourth edition FanPro
  if ($a_runners['runr_version'] == 25) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# note the 1st - 4th are copies of 20th anniversary. just need to update (if it even exists)
# 4th 20th Anniversary
  if ($a_runners['runr_version'] == 42) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# fifth edition
  if ($a_runners['runr_version'] == 2) {
    $composure  = "CHA+WIL:152";
    $comvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_willpower']);
    $intentions = "CHA+INT:152";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:152";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "BOD+STR:152";
    $lftvalue   = ($a_runners['runr_body'] + $a_runners['runr_strength']);
  }

# sixth world
  if ($a_runners['runr_version'] == 31) {
    $composure  = "WIL+CHA:67";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "WIL+INT:67";
    $intvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_intuition']);
    $memory     = "LOG+INT:67";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_intuition']);
    $liftcarry  = "BOD+WIL:67";
    $lftvalue   = ($a_runners['runr_body'] + $a_runners['runr_willpower']);
  }

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Attributes</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" width=\"18%\">Physical</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"18%\">Mental</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"18%\">Special</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"28%\">Initiative</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"18%\">Derived</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Body</strong>: "         . $a_runners['runr_body']      . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Willpower</strong>: " . $a_runners['runr_willpower'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Edge</strong>: "       . $a_runners['runr_totaledge']  . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Initiative</strong>: " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $composure . "\"><strong>Composure</strong>: "  . $comvalue . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Agility</strong>: "    . $a_runners['runr_agility']    . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Logic</strong>: "      . $a_runners['runr_logic']    . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Essence</strong>: "      . $a_runners['runr_essence']   . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"INT*2\"><strong>Astral</strong>: " . ($a_runners['runr_intuition'] + $a_runner['runr_intuition']) . " + 2d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $intention . "\"><strong>Judge Intent</strong>: " . $intvalue . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Reaction</strong>: "   . $a_runners['runr_reaction'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Intuition</strong>: "    . $a_runners['runr_intuition'] . "</td>";
  $initiate = '';
  if ($a_runners['runr_initiate'] > 0) {
    $initiate = " (" . ($a_runners['runr_magic'] + $a_runners['runr_initiate']) . ")";
  }
  $output .= "  <td class=\"ui-widget-content\"><strong>Magic</strong>: "      . $a_runners['runr_magic']    . $initiate . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Matrix: AR</strong> " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
  if ($data_processing > 0) {
    $output .= "/<strong>cold-sim VR</strong>: " . ($data_processing + $a_runners['runr_intuition']) . " + 3d6";
    $output .= "/<strong>hot-sim VR</strong>: " . ($data_processing + $a_runners['runr_intuition']) . " + 4d6";
  }
  $output .= "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $liftcarry . "\"><strong>Lift/Carry</strong>: " . $lftvalue . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Strength</strong>: "  . $a_runners['runr_strength']  . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Charisma</strong>: "   . $a_runners['runr_charisma']   . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Resonance</strong>: " . $a_runners['runr_resonance'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Rigging AR</strong>: " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $memory . "\"><strong>Memory</strong>: "    . $memvalue . "</td>";
  $output .= "</tr>";
  $output .= "</table>";

  if ($a_runners['ver_version'] == 5.0) {
    $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
    $output .= "<tr>";
    $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Limits</th>";
    $output .= "</tr>";

    $mental_limit   = ceil((($a_runners['runr_logic'] * 2)    + $a_runners['runr_intuition'] + $a_runners['runr_willpower']) /3);
    $physical_limit = ceil((($a_runners['runr_strength'] * 2) + $a_runners['runr_body']      + $a_runners['runr_reaction'])  /3);
    $social_limit   = ceil((($a_runners['runr_charisma'] * 2) + $a_runners['runr_willpower'] + $a_runners['runr_essence'])   /3);
    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content\" title=\"((LOGx2)+INT+WIL)/3 sr5/101\"><strong>Mental</strong>: "   . $mental_limit   . "</td>";
    $output .= "  <td class=\"ui-widget-content\" title=\"((STRx2)+BOD+REA)/3 sr5/101\"><strong>Physical</strong>: " . $physical_limit . "</td>";
    $output .= "  <td class=\"ui-widget-content\" title=\"((CHAx2)+WIL+ESS)/3 sr5/101\"><strong>Social</strong>: "   . $social_limit   . "</td>";
    $output .= "</tr>";
    $output .= "</table>";
  }

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Various Statistics</th>";
  $output .= "</tr>";

  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\" title=\"SR5: 161 AGI*2\"><strong>Walking Rate</strong>: " . ($a_runners['runr_agility'] * 2) . " meters per turn</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"SR5: 161 AGI*4\"><strong>Running Rate</strong>: " . ($a_runners['runr_agility'] * 4) . " meters per turn</td>";


  $sprinting = (($a_runners['runr_agility'] * 4) + $a_runners['runr_strength']) . " [" . $physical_limit  . "]";

  $sprint_speed = "1 meter";
  if ($a_runners['meta_name'] == 'Human' || $a_runners['meta_name'] == 'Elf' || $a_runners['meta_name'] == 'Ork') {
    $sprint_speed = "2 meters";
  }

  $output .= "  <td class=\"ui-widget-content\" title=\"SR5: 161 AGI*4+STR[Physical]\"><strong>Sprinting Test</strong>: " . $sprinting . " = " . ($a_runners['runr_agility'] * 4) . " +" . $sprint_speed . " per success</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Swimming Rate</strong>: " . $a_runners['meta_swim'] . " meters per turn</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Lift without Test</strong>: " . ($a_runners['runr_strength'] * 15) . " kg</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Overhead Lift without test</strong>: " . ($a_runners['runr_strength'] * 5) . " kg</td>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Combat Statistics</th>";
  $output .= "</tr>";

  $q_string  = "select act_id,act_attribute,act_default ";
  $q_string .= "from active ";
  $q_string .= "where act_name = 'Gymnastics' ";
  $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_active = mysql_fetch_array($q_active);

  $q_string  = "select r_act_id,r_act_rank ";
  $q_string .= "from r_active ";
  $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " ";
  $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_active) > 0) {
    $a_r_active = mysql_fetch_array($q_r_active);
    $dodge = $a_r_active['r_act_rank'];
  } else {
    $dodge = 0;
  }

  $weaponskill = 0;
  $counterspell = 0;

  $q_string  = "select act_id,act_attribute,act_default ";
  $q_string .= "from active ";
  $q_string .= "where act_name = 'Unarmed Combat' ";
  $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_active = mysql_fetch_array($q_active);

  $q_string  = "select r_act_id,r_act_rank ";
  $q_string .= "from r_active ";
  $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " ";
  $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_active) > 0) {
    $a_r_active = mysql_fetch_array($q_r_active);
    $unarmedcombat = $a_r_active['r_act_rank'];
  } else {
    $unarmedcombat = 0;
  }

  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\" title=\"SR5: 173 REA+INT\"><strong>Ranged Defense</strong>: " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Ranged Full Defense</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Dodge (" . $dodge . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Parry</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Weapon Skill (" . $weaponskill . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Block</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Unarmed Combat (" . $unarmedcombat . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Dodge</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Dodge (" . $dodge . ")</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Full Parry</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Weapon Skill (" . $weaponskill . ") + Dodge (" . $dodge . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Full Block</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Unarmed Combat (" . $unarmedcombat . ") + Dodge (" . $dodge . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Melee Full Dodge</strong>: Reaction (" . $a_runners['runr_reaction'] . ") + Dodge (" . $dodge . ") + Dodge (" . $dodge . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Physical Spell Defense</strong>: Body (" . $a_runners['runr_body'] . ") + Counterspell (" . $counterspell . ")</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Mana Spell Defense</strong>: Willpower (" . $a_runners['runr_willpower'] . ") + Counterspell (" . $counterspell . ")</td>";
  $output .= "</tr>";
  $output .= "</table>";

?>

document.getElementById('detail_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

