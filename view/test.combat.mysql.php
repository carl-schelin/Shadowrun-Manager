<?php
# Script: test.combat.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.combat.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 158</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Roll Initiative - sr5-159</li>\n";
  $output .= "  <li>Begin Initiative Pass. Highest to lowest.</li>\n";
  $output .= "  <li>Declare Actions - 1 free and 2 Simple or 1 complex.\n";
  $output .= "    <ol type=\"a\"><li>Free: Call a Shot - Pg 163 ref: Pg 178</li>\n";
  $output .= "                   <li>Free: Changed Linked Device Mode - pg 163</li>\n";
  $output .= "                   <li>Free: Drop Object - pg 163</li>\n";
  $output .= "                   <li>Free: Drop Prone - pg 164 ref: pg 192</li>\n";
  $output .= "                   <li>Free: Eject Smartgun Clip - pg 164 ref: pg 433</li>\n";
  $output .= "                   <li>Free: Gesture - pg 164</li>\n";
  $output .= "                   <li>Free: Multiple Attacks - pg 164</li>\n";
  $output .= "                   <li>Free: Run - pg 164</li>\n";
  $output .= "                   <li>Free: Speak/Text/Transmit Phrase - pg 164</li>\n";
  $output .= "                   <li>Simple: Active Focus</li>\n";
  $output .= "                   <li>Simple: Call Spirit</li>\n";
  $output .= "                   <li>Simple: Change Device Mode</li>\n";
  $output .= "                   <li>Simple: Command Spirit</li>\n";
  $output .= "                   <li>Simple: Dismiss Spirit</li>\n";
  $output .= "                   <li>Simple: Fire Bow</li>\n";
  $output .= "                   <li>Simple: Fire Weapon (SA, SS, BF, FA)</li>\n";
  $output .= "                   <li>Simple: Insert Clip</li>\n";
  $output .= "                   <li>Simple: Observe in Detail</li>\n";
  $output .= "                   <li>Simple: Pick Up/Put Down Object</li>\n";
  $output .= "                   <li>Simple: Quick Draw</li>\n";
  $output .= "                   <li>Simple: Ready/Draw Weapon</li>\n";
  $output .= "                   <li>Simple: Reckless Spellcasting</li>\n";
  $output .= "                   <li>Simple: Reload Weapon (see Table)</li>\n";
  $output .= "                   <li>Simple: Remove Clip</li>\n";
  $output .= "                   <li>Simple: Shift Perception</li>\n";
  $output .= "                   <li>Simple: Take Aim</li>\n";
  $output .= "                   <li>Simple: Take Cover</li>\n";
  $output .= "                   <li>Simple: Throw Weapon</li>\n";
  $output .= "                   <li>Simple: Use Simple Device</li>\n";
  $output .= "                   <li>Complex: Astral Projection</li>\n";
  $output .= "                   <li>Complex: Banish Spirit</li>\n";
  $output .= "                   <li>Complex: Cast Spell</li>\n";
  $output .= "                   <li>Complex: Fire Weapon (FA)</li>\n";
  $output .= "                   <li>Complex: Fire Long or Semi-Auto Burst</li>\n";
  $output .= "                   <li>Complex: Fire Mounted or Vehicle Weapon</li>\n";
  $output .= "                   <li>Complex: Melee Attack</li>\n";
  $output .= "                   <li>Complex: Reload Weapon (see Table)</li>\n";
  $output .= "                   <li>Complex: Rigger Jump In</li>\n";
  $output .= "                   <li>Complex: Sprint</li>\n";
  $output .= "                   <li>Complex: Summoning</li>\n";
  $output .= "                   <li>Complex: Use Skill</li>\n";
  $output .= "                   <li>Interrupt: Block</li>\n";
  $output .= "                   <li>Interrupt: Dodge</li>\n";
  $output .= "                   <li>Interrupt: Full Defense</li>\n";
  $output .= "                   <li>Interrupt: Hit the Dirt</li>\n";
  $output .= "                   <li>Interrupt: Intercept</li>\n";
  $output .= "                   <li>Interrupt: Parry</li>\n";
  $output .= "  </ol></li>\n";
  $output .= "  <li>Resolve Actions</li>\n";
  $output .= "  <li>Subtract 10 from the Initiative, who is ever above 10, goes again until all are at or below zero.</li>\n";
  $output .= "  <li>Start over</li>\n";
  $output .= "</ol>\n";

  print "document.getElementById('test_combat_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
