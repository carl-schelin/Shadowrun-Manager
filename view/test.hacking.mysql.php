<?php
# Script: test.hacking.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "test.hacking.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<p>Starts SR5 - Page </p>\n";
  $output .= "<p></p>\n";
  $output .= "<ol>\n";
  $output .= "  <li></li>\n";
  $output .= "</ol>\n";


  $output .= "<p>Hacking SR4 236</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Exploit (pg: )</li>\n";
  $output .= "  <li>Stealth (pg: )</li>\n";
  $output .= "  <li>Hacking (pg: )</li>\n";
  $output .= "  <li>Extended TEst (pg: )</li>\n";
  $output .= "  <li>Personal, Security, Admin (pg: )</li>\n";
  $output .= "</ul>\n";

  $output .= "<p>Node Info</p>\n";
  $output .= "<ul>\n";
  $output .= "  <li>Analyze (pg: )</li>\n";
  $output .= "  <li>System (pg: )</li>\n";
  $output .= "  <li>Firewall? (pg: )</li>\n";
  $output .= "</ul>\n";

  print "document.getElementById('test_hacking_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
