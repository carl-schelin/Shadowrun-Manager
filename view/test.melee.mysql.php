<?php
# Script: test.melee.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.melee.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 184</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Declare</li>\n";
  $output .= "  <li>Attack</li>\n";
  $output .= "  <li>Defend</li>\n";
  $output .= "  <li>Apply Effect</li>\n";
  $output .= "</ol>\n";
  $output .= "<p>Opposed Test: Combat Skill + Agility [Weapon Accuracy] vs Options:</br>";
  $output .= "1. Free Action: Reaction + Intuition</br>";
  $output .= "2. Interrupt (-5 init): Parry (has weapon): Reaction + Intuition + Weapon Skill [Physical]</br>";
  $output .= "3. Interrupt (-5 init): Block (empty handed): Reaction + Intuition + Unarmed Combat Skill [Physical]</br>";
  $output .= "4. Interrupt (-5 init): Dodge: Reaction + Intuition + Gymnastics [Physical]</br>";
  $output .= "5. Interrupt (-10 init): Full Defense: Reaction + Intuition + Willpower [Physical]</p>";

  print "document.getElementById('test_melee_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
