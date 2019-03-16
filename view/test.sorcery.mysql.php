<?php
# Script: test.sorcery.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.sorcery.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 281</p>\n";
  $output .= "<p>Spellcasting</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Choose Spell</li>\n";
  $output .= "  <li>Choose the Target</li>\n";
  $output .= "  <li>Choose Spell Force - Up to twice; if hits [limit] exceed Magic rating, then Physical damage, otherwise Stun</li>\n";
  $output .= "  <li>Cast Spell - Spellcasting + Magic [Force].</li>\n";
  $output .= "  <li>Determine Effect</li>\n";
  $output .= "  <li>Resist Drain - Tradition drain pool. Cannot be lower than 2. </li>\n";
  $output .= "  <li>Determine Ongoing Effects</li>\n";
  $output .= "</ol>\n";

  $output .= "<p>Counterspelling</p>\n";
  $output .= "<p>Used to aid others by adding to their or your pool. Or Dispelling to counter a sustained or quickened spell</p>\n";

  $output .= "<p>Ritual Spellcasting</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Choose Ritual Leader</li>\n";
  $output .= "  <li>Choose Ritual</li>\n";
  $output .= "  <li>Choose the Force of the Ritual Spell</li>\n";
  $output .= "  <li>Set Up The Foundatin</li>\n";
  $output .= "  <li>Give The Offering</li>\n";
  $output .= "  <li>Perform The Ritual</li>\n";
  $output .= "  <li>Seal The Ritual</li>\n";
  $output .= "</ol>\n";


  $output .= "<p>Spellcasting from SR4: 182</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Spell Force (pg: )</li>\n";
  $output .= "  <li>Magic (pg: )</li>\n";
  $output .= "  <li>Spellcasting (pg: )</li>\n";
  $output .= "  <li>Additional Sustained Spells (pg: )</li>\n";
  $output .= "  <li>Visibility Modifier (pg: )</li>\n";
  $output .= "  <li>Mentor Spirit MOdifier (pg: )</li>\n";
  $output .= "  <li>Spellcasting focus (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Target</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Spell: Physical or Mana (pg: )</li>\n";
  $output .= "  <li>Body (pg: )</li>\n";
  $output .= "  <li>Willpower (pg: )</li>\n";
  $output .= "  <li>Counterspelling Dice (pg: )</li>\n";
  $output .= "  <li>Astral Barrier Force (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Spellcaster drain</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Force (pg: )</li>\n";
  $output .= "  <li>Modifier (pg: )</li>\n";
  $output .= "  <li>Willpower (pg: )</li>\n";
  $output .= "  <li>Drain Attribute based on Tradition (pg: )</li>\n";
  $output .= "  <li>Spellcasting Focus (pg: )</li>\n";
  $output .= "</ul>\n";

  print "document.getElementById('test_sorcery_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
