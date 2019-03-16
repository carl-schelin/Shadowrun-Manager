<?php
# Script: test.ranged.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.ranged.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 172</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Declare</li>\n";
  $output .= "  <li>Attack</li>\n";
  $output .= "  <li>Defend</li>\n";
  $output .= "  <li>Apply Effect</li>\n";
  $output .= "</ol>\n";
  $output .= "<p>Opposed Test: Weapon Skill + Agility [Weapon Accuracy] vs Reaction + Intuition (+ Willpower and -10 to Initiative if Full Defense)</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Environmental</li>\n";
  $output .= "  <li>Recoil</li>\n";
  $output .= "  <li>Situational</li>\n";
  $output .= "  <li>Wound</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Modifiers from SR4: Pg 150</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Fire Mode (pg: )</li>\n";
  $output .= "  <li>Agility (pg: )</li>\n";
  $output .= "  <li>Edge (pg: )</li>\n";
  $output .= "  <li>Weapon Skill (pg: )</li>\n";
  $output .= "  <li>Burst mode or Shot (pg: )</li>\n";
  $output .= "  <li>In Melee (enemy < 2m) (pg: )</li>\n";
  $output .= "  <li>Running (pg: )</li>\n";
  $output .= "  <li>Firing from Cover? (pg: )</li>\n";
  $output .= "  <li>In a moving vehicle (pg: )</li>\n";
  $output .= "  <li>Laser or smartlink Active (pg: )</li>\n";
  $output .= "  <li>Long Shot (use 1 edge) (pg: )</li>\n";
  $output .= "  <li>Off Hand (pg: )</li>\n";
  $output .= "  <li>Area Effect (grenade, missile...) (pg: )</li>\n";
  $output .= "  <li>Heavy Weapon? (pg: )</li>\n";
  $output .= "  <li>Dumpshocked? (pg: )</li>\n";
  $output .= "  <li>Gyro-Stabilized? (pg: )</li>\n";
  $output .= "  <li>Ambidextrous? (pg: )</li>\n";
  $output .= "  <li>Home Ground? (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "  <li>Range Modifier (0 to -3) (pg: )</li>\n";
  $output .= "  <li>Vision Modifier (pg: )</li>\n";
  $output .= "  <li>Called Shot (DP/DV) (pg: )</li>\n";
  $output .= "  <li>Recoil Compensation (Full Comp) (pg: )</li>\n";
  $output .= "  <li>Aimed Shot? (+1DP per delay) (pg: )</li>\n";
  $output .= "  <li>Additional Targets this phase (pg: )</li>\n";
  $output .= "  <li>Shotgun? Spread Narrow, Medium, Wide (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Defender</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Reaction: (pg: )</li>\n";
  $output .= "  <li>Full Defense (pg: )</li>\n";
  $output .= "  <li>Dodge (pg: )</li>\n";
  $output .= "  <li>Gymnastics (pg: )</li>\n";
  $output .= "  <li>Cover (%) 0, 25, 50, 75, 100 (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "  <li>Number of Previous Attacks (pg: )</li>\n";
  $output .= "  <li>Dumpshocked (pg: )</li>\n";
  $output .= "  <li>Prone (pg: )</li>\n";
  $output .= "  <li>Home Ground (pg: )</li>\n";
  $output .= "  <li>In a moving vehicle (pg: )</li>\n";
  $output .= "  <li>In a melee combat (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Soak</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Body: (pg: )</li>\n";
  $output .= "  <li>Ballistic: (pg: )</li>\n";
  $output .= "  <li>Modified: (pg: )</li>\n";
  $output .= "  <li>Impact: (pg: )</li>\n";
  $output .= "  <li>Modified: (pg: )</li>\n";
  $output .= "</ul>\n";

  print "document.getElementById('test_ranged_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
