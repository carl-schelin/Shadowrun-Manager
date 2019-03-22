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
  $q_string .= "runr_logic,runr_willpower,runr_essence,runr_totaledge,";
  $q_string .= "runr_currentedge,runr_magic,runr_resonance,runr_age,runr_sex,runr_height,";
  $q_string .= "runr_weight,runr_totalkarma,runr_currentkarma,";
  $q_string .= "runr_physicalcon,runr_stuncon,runr_desc,";
  $q_string .= "runr_sop,runr_available,meta_name,meta_walk,meta_run,meta_swim ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
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

  $output .= "<ul>";
  $output .= "  <li><strong>System Information</strong>";
  $output .= "  <ul>";
  $output .= "    <li><strong>911 Call Path</strong> - If a system is in the 911 call path, <span class=\"ui-state-error\">the header</span> and <span class=\"ui-state-highlight\">this text</span> will indicate it.</li>";
  $output .= "    <li><strong>Server</strong> - The hostname of a server according to DNS.</li>";
  $output .= "    <li><strong>Function</strong> - The system function; Oracle Database Server, JBoss Application Server, etc.</li>";
  $output .= "    <li><strong>Product</strong> - The Intrado product this server is a member of.</li>";
  $output .= "    <li><strong>Managed By</strong> - The group that provides overall management of the system.</li>";
  $output .= "    <li><strong>Class</strong> - The Product's service class. The system is one component of the Product. The Product may require 2 9's of uptime but a component like this server can have a higher failure rate.</li>";
  $output .= "    <li><strong>Availability </strong> - The hours and days the <strong>Product</strong> must be available for use.</li>";
  $output .= "    <li><strong>Downtime</strong> - The amount of downtime permitted for this <strong>Product</strong>.</li>";
  $output .= "    <li><strong>MTBF</strong> - The Mean Time Between Failure for this <strong>Product</strong>.</li>";
  $output .= "    <li><strong>Geographic Diversity</strong> - Does this <strong>Product</strong> require Geographic Diversity? This system may be one of a group located in diverse data centers.</li>";
  $output .= "    <li><strong>MTTR</strong> - The Mean Time To Recovery for this <strong>Product</strong>. How long it can take to recover this <strong>Product</strong>.</li>";
  $output .= "    <li><strong>Resource Sharing</strong> - Can other Products coexist on this server?</li>";
  $output .= "    <li><strong>Restore</strong> - Max time to Restore service for this <strong>Product</strong></li>";
  $output .= "  </ul></li>";
  $output .= "  <li><strong>Location Information</strong> - Data Center name, Address, Rack location if appropriate, and if available, a link to a document on data center access requirements.</li>";
  $output .= "  <li><strong>Pictures</strong> - Any pictures of the front or rear of a physical system.</li>";
  $output .= "  <li><strong>System Timeline</strong>";
  $output .= "  <ul>";
  $output .= "    <li><strong>Purchased</strong> - The date the system was purchased.</li>";
  $output .= "    <li><strong>Built</strong> - The date the Operating System was installed and the system accessible to users.</li>";
  $output .= "    <li><strong>Go Live</strong> - The date the system passed InfoSec scans and went live.</li>";
  $output .= "    <li><strong>End of Life</strong> - The anticipated date the system will be retired.</li>";
  $output .= "    <li><strong>Retired</strong> - The date the system was retired.</li>";
  $output .= "    <li><strong>Reallocated</strong> - The date the system was reused in another capacity. Generally systems are disposed of but in a few cases, systems are reused for other tasks. Note new use in Notes field.</li>";
  $output .= "  </ul></li>";
  $output .= "  <li><strong>Platform Specific Information</strong> - These fields are included by system custodians to provide group specific information.</li>";
  $output .= "  <li><strong>External Links</strong> - A list of external links. These links take you to web pages hosted here or on other systems.</li>";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Personal Data</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\"><strong>Alias</strong>: " . $a_runners['runr_aliases'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Archetype</strong>: " . $a_runners['runr_archetype'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Metatype</strong>: " . $a_runners['meta_name'] . "</td>";
  $output .= "</tr>";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Name</strong>: " . $a_runners['runr_name'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Total Karma</strong>: " . $a_runners['runr_totalkarma'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\"><strong>Current Karma</strong>: " . $a_runners['runr_currentkarma'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\"><strong>Current Edge</strong>: "     . $a_runners['runr_currentedge'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>";

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
  $output .= "  <td class=\"ui-widget-content\"><strong>Agility</strong>: "    . $a_runners['runr_agility']    . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Charisma</strong>: "   . $a_runners['runr_charisma']   . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Edge</strong>: "       . $a_runners['runr_totaledge']  . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Initiative</strong>: " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"WIL+CHA sr5/152\"><strong>Composure</strong>: "  . ($a_runners['runr_willpower'] + $a_runners['runr_charisma']) . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Body</strong>: "         . $a_runners['runr_body']      . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Intuition</strong>: "    . $a_runners['runr_intuition'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Essence</strong>: "      . $a_runners['runr_essence']   . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"INT*2\"><strong>Astral</strong>: " . ($a_runners['runr_intuition'] + $a_runner['runr_intuition']) . " + 2d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"INT+CHA sr5/152\"><strong>Judge Intent</strong>: " . ($a_runners['runr_intuition'] + $a_runners['runr_charisma']) . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Reaction</strong>: "   . $a_runners['runr_reaction'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Logic</strong>: "      . $a_runners['runr_logic']    . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Magic</strong>: "      . $a_runners['runr_magic']    . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Matrix: AR</strong> " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
  if ($data_processing > 0) {
    $output .= "/<strong>cold-sim VR</strong>: " . ($data_processing + $a_runners['runr_intuition']) . " + 3d6";
    $output .= "/<strong>hot-sim VR</strong>: " . ($data_processing + $a_runners['runr_intuition']) . " + 4d6";
  }
  $output .= "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"STR+BOD sr5/152\"><strong>Lift/Carry</strong>: " . ($a_runners['runr_strength'] + $a_runners['runr_body']) . "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Strength</strong>: "  . $a_runners['runr_strength']  . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Willpower</strong>: " . $a_runners['runr_willpower'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Resonance</strong>: " . $a_runners['runr_resonance'] . "</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"REA+INT\"><strong>Rigging AR</strong>: " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>";
  $output .= "  <td class=\"ui-widget-content\" title=\"LOG+WIL sr5/152\"><strong>Memory</strong>: "    . ($a_runners['runr_logic'] + $a_runners['runr_willpower']) . "</td>";
  $output .= "</tr>";
  $output .= "</table>";

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

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Various Statistics</th>";
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
  $output .= "<tr>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Weight</strong>: " . $a_runners['runr_weight'] . " lb</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Height</strong>: " . $a_runners['runr_height'] . " inches</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Sex</strong>: " . ($a_runners['runr_sex'] == 0 ? 'Female' : 'Male') . "</td>";
  $output .= "  <td class=\"ui-widget-content\"><strong>Age</strong>: " . $a_runners['runr_age'] . " Years</td>";
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

