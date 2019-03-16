<?php
# Script: test.enchanting.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.enchanting.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 304</p>\n";
  $output .= "<p>Alchemy - Creating a Preparation</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Choose A Spell</li>\n";
  $output .= "  <li>Choose Spell Force - Force up to twice Magic</li>\n";
  $output .= "  <li>Choose The Lynchpin For The Preparation - An object you can handle; small enough to lift.</li>\n";
  $output .= "  <li>Choose Preparation Trigger - Command, Contact, or Timed</li>\n";
  $output .= "  <li>Create The Preparation - Alchemy + Magic [Force]. You can use reagents to increase [limit]</li>\n";
  $output .= "  <li>Resist Drain - If number of hits exceeds Magic rating, it's Physical Damage, otherwise Stun.</li>\n";
  $output .= "</ol>\n";

  $output .= "<p>Artificing - Creating a Focus</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Choose Focus Formula</li>\n";
  $output .= "  <li>Obtain The Telesma</li>\n";
  $output .= "  <li>Prepare The Magical Lodge</li>\n";
  $output .= "  <li>Spend Reagents</li>\n";
  $output .= "  <li>Craft The Focus</li>\n";
  $output .= "  <li>Resist Drain</li>\n";
  $output .= "</ol>\n";

  print "document.getElementById('test_enchanting_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
