<?php
# Script: test.astral.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.astral.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 315</p>\n";
  $output .= "<p>Astral Combat - Same as Physical combat except no ranged combat (yes spells).</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Agility is replaced by Logic</li>\n";
  $output .= "  <li>Body is replaced by Willpower</li>\n";
  $output .= "  <li>Reaction is replaced by Intuition</li>\n";
  $output .= "  <li>Strength is replaced by Charisma</li>\n";
  $output .= "  <li>Normal combat for physical or dual natured</li>\n";
  $output .= "  <li>Astral Combat + willpower for astral entities</li>\n";
  $output .= "  <li>Unarmed: Astral Combat + Willpower [Astral] vs Intuition + Logic, DV is Charisma</li>\n";
  $output .= "  <li>Weapon Foci: Astral Combat + Willpower [Accuracy] vs Intuition + Logic, DV is by weapon; CHA vs STR</li>\n";
  $output .= "  <li>Spirit DV is Force</li>\n";
  $output .= "  <li>Watcher DV is 1</li>\n";
  $output .= "</ol>\n";

  $output .= "<p>From sR4: 184</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Attacker is a Mage, Spirit, or Watcher Spirit (pg: )</li>\n";
  $output .= "  <li>Willpower (pg: )</li>\n";
  $output .= "  <li>Logic (pg: )</li>\n";
  $output .= "  <li>Astral Combat (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "  <li>Reach: apply to DP or Opponant (pg: )</li>\n";
  $output .= "  <li>Weapon Focus Rating (pg: )</li>\n";
  $output .= "  <li>Damage Value (pg: )</li>\n";
  $output .= "  <li>Physical or Stun (pg: )</li>\n";
  $output .= "  <li>Armor Penetration (pg: )</li>\n";
  $output .= "  <li>Reach (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Defender</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Reaction: (pg: )</li>\n";
  $output .= "  <li>Defender will Parry, Block or Dodge: (pg: )</li>\n";
  $output .= "  <li>Weapon Skill: (pg: )</li>\n";
  $output .= "  <li>Full Defense: (pg: )</li>\n";
  $output .= "  <li>Dodge: (pg: )</li>\n";
  $output .= "  <li>Wound Modifier: (pg: )</li>\n";
  $output .= "  <li>Number of Previous Attacks: (pg: )</li>\n";
  $output .= "  <li>Dumpshocked: (pg: )</li>\n";
  $output .= "  <li>Receiving Charge: (pg: )</li>\n";
  $output .= "  <li>Prone: (pg: )</li>\n";
  $output .= "  <li>Running: (pg: )</li>\n";
  $output .= "  <li>Body: (pg: )</li>\n";
  $output .= "  <li>Impact: (pg: )</li>\n";
  $output .= "  <li>Modifier: (pg: )</li>\n";
  $output .= "</ul>\n";

  print "document.getElementById('test_astral_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
