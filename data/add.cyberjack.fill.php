<?php
# Script: add.cyberjack.fill.php
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
    $package = "add.cyberjack.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberjack");

      $q_string  = "select jack_name,jack_rating,jack_data,jack_firewall,jack_matrix,jack_essence,jack_avail,";
      $q_string .= "jack_perm,jack_cost,jack_book,jack_page ";
      $q_string .= "from cyberjack ";
      $q_string .= "where jack_id = " . $formVars['id'];
      $q_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_cyberjack = mysql_fetch_array($q_cyberjack);
      mysql_free_result($q_cyberjack);

      print "document.dialog.jack_name.value = '"      . mysql_real_escape_string($a_cyberjack['jack_name'])      . "';\n";
      print "document.dialog.jack_rating.value = '"    . mysql_real_escape_string($a_cyberjack['jack_rating'])    . "';\n";
      print "document.dialog.jack_essence.value = '"   . mysql_real_escape_string($a_cyberjack['jack_essence'])   . "';\n";
      print "document.dialog.jack_data.value = '"      . mysql_real_escape_string($a_cyberjack['jack_data'])      . "';\n";
      print "document.dialog.jack_firewall.value = '"  . mysql_real_escape_string($a_cyberjack['jack_firewall'])  . "';\n";
      print "document.dialog.jack_matrix.value = '"    . mysql_real_escape_string($a_cyberjack['jack_matrix'])    . "';\n";
      print "document.dialog.jack_avail.value = '"     . mysql_real_escape_string($a_cyberjack['jack_avail'])     . "';\n";
      print "document.dialog.jack_perm.value = '"      . mysql_real_escape_string($a_cyberjack['jack_perm'])      . "';\n";
      print "document.dialog.jack_cost.value = '"      . mysql_real_escape_string($a_cyberjack['jack_cost'])      . "';\n";
      print "document.dialog.jack_book.value = '"      . mysql_real_escape_string($a_cyberjack['jack_book'])      . "';\n";
      print "document.dialog.jack_page.value = '"      . mysql_real_escape_string($a_cyberjack['jack_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
