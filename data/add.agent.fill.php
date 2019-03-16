<?php
# Script: add.agent.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.agent.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from agent");

      $q_string  = "select agt_name,agt_rating,agt_avail,agt_perm,agt_cost,agt_book,agt_page ";
      $q_string .= "from agents ";
      $q_string .= "where agt_id = " . $formVars['id'];
      $q_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_agents = mysql_fetch_array($q_agents);
      mysql_free_result($q_agents);

      print "document.dialog.agt_name.value = '"   . mysql_real_escape_string($a_agents['agt_name'])  . "';\n";
      print "document.dialog.agt_rating.value = '" . mysql_real_escape_string($a_agents['agt_rating'])  . "';\n";
      print "document.dialog.agt_avail.value = '"  . mysql_real_escape_string($a_agents['agt_avail']) . "';\n";
      print "document.dialog.agt_perm.value = '"   . mysql_real_escape_string($a_agents['agt_perm'])  . "';\n";
      print "document.dialog.agt_cost.value = '"   . mysql_real_escape_string($a_agents['agt_cost'])  . "';\n";
      print "document.dialog.agt_book.value = '"   . mysql_real_escape_string($a_agents['agt_book'])  . "';\n";
      print "document.dialog.agt_page.value = '"   . mysql_real_escape_string($a_agents['agt_page'])  . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
