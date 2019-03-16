<?php
# Script: test.matrix.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.matrix.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page </p>\n";
  $output .= "<p></p>\n";
  $output .= "<ol>\n";
  $output .= "  <li></li>\n";
  $output .= "</ol>\n";


  $output .= "<p>Matrix Combat</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Attacker: Icon or Digital (pg: )</li>\n";
  $output .= "  <li>Attack Program Rating (pg: )</li>\n";
  $output .= "  <li>Black IC? (pg: )</li>\n";
  $output .= "  <li>Cybercombat (pg: )</li>\n";
  $output .= "  <li>matrix Wound Modifier (pg: )</li>\n";
  $output .= "  <li>Simsense Link, Cold/Hot (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Defender</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Response (pg: )</li>\n";
  $output .= "  <li>Firewall (pg: )</li>\n";
  $output .= "  <li>Full Defense (pg: )</li>\n";
  $output .= "  <li>Hacking (pg: )</li>\n";
  $output .= "  <li>Simsinse Link hot/cold (pg: )</li>\n";
  $output .= "  <li>Matrix Wound Modifier (pg: )</li>\n";
  $output .= "  <li>Soak System (pg: )</li>\n";
  $output .= "  <li>Soak Armor (pg: )</li>\n";
  $output .= "</ul>\n";


  print "document.getElementById('test_matrix_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
