<?php
# Script: test.conjuring.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.conjuring.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page 300</p>\n";
  $output .= "<ol>\n";
  $output .= "  <li>Choose Spirit Type and Force - Based on your Tradition, and up to Twice Magic</li>\n";
  $output .= "  <li>Attempt Summoning - Opposed test: Summoning + Magic [Force] vs Spirit's Force. Spend reagents to change the [limit]</li>\n";
  $output .= "  <li>Resist Drain - DV = Spirit Hits x 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>\n";
  $output .= "</ol>\n";

  $output .= "<ol>\n";
  $output .= "  <li>Attempt Binding - Opposed test: Summoning + Magic [Force] vs Spirit's Force. Spend reagents to change the [limit]</li>\n";
  $output .= "  <li>Resist Drain - DV = Spirit Hits x 2, minimum of 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>\n";
  $output .= "</ol>\n";

  $output .= "<ol>\n";
  $output .= "  <li>Attempt Banishing - Opposed test: Banishing + Magic [Astral] vs Spirit's Force. Spend reagents to change the [limit]</li>\n";
  $output .= "  <li>Resist Drain - DV = Spirit Hits x 2, minimum of 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>\n";
  $output .= "</ol>\n";



  $output .= "<p>Summoning: SR4 page 188</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Magic (pg: )</li>\n";
  $output .= "  <li>Summoning Skill (pg: )</li>\n";
  $output .= "  <li>Mentor Spirit Modifier (pg: )</li>\n";
  $output .= "  <li>Summoning Focus (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Spirit</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Spirit Force (pg: )</li>\n";
  $output .= "  <li>GM Rolls Spirit Force (pg: )</li>\n";
  $output .= "  <li>Number of Services (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Spellcaster Drain</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Willpower (pg: )</li>\n";
  $output .= "  <li>Drain Attribute based on Tradition (pg: )</li>\n";
  $output .= "  <li>Summoning focus (pg: )</li>\n";
  $output .= "  <li>Focused Concentration No, Lvl1, Lvl 2 (pg: )</li>\n";
  $output .= "</ul>\n";


  $output .= "<p>Binding: SR4 page 188</p>\n";


  $output .= "<p>Banishing: SR4 page 188</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Magic (pg: )</li>\n";
  $output .= "  <li>Banishing Skill (pg: )</li>\n";
  $output .= "  <li>Mentor Spirit Modifier (pg: )</li>\n";
  $output .= "  <li>Banishing Focus (pg: )</li>\n";
  $output .= "  <li>Wound Modifier (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Spirit</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Spirit Force (Bound?) (pg: )</li>\n";
  $output .= "  <li>Summoners Magic (pg: )</li>\n";
  $output .= "  <li>GM Rolls Spirit Force (pg: )</li>\n";
  $output .= "  <li>Number of Services Reduced (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Spellcaster Drain</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Willpower (pg: )</li>\n";
  $output .= "  <li>Drain Attribute based on Tradition (pg: )</li>\n";
  $output .= "  <li>Banishing focus (pg: )</li>\n";
  $output .= "  <li>Focused Concentration No, Lvl1, Lvl 2 (pg: )</li>\n";
  $output .= "</ul>\n";



  print "document.getElementById('test_conjuring_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
