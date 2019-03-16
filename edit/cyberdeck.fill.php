<?php
# Script: cyberdeck.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "cyberdeck.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_cyberdeck");

      $q_string  = "select deck_brand,deck_model,r_deck_attack,r_deck_sleaze,r_deck_data,r_deck_firewall ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
      $q_string .= "where r_deck_id = " . $formVars['id'];
      $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_cyberdeck = mysql_fetch_array($q_r_cyberdeck);
      mysql_free_result($q_r_cyberdeck);

      print "document.getElementById('r_deck_item').innerHTML = '" . mysql_real_escape_string($a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model']) . "';\n\n";

      print "document.getElementById('attack_val').innerHTML = '"   . mysql_real_escape_string($a_r_cyberdeck['r_deck_attack']) . "';\n";
      print "document.getElementById('sleaze_val').innerHTML = '"   . mysql_real_escape_string($a_r_cyberdeck['r_deck_sleaze']) . "';\n";
      print "document.getElementById('data_val').innerHTML = '"     . mysql_real_escape_string($a_r_cyberdeck['r_deck_data']) . "';\n";
      print "document.getElementById('firewall_val').innerHTML = '" . mysql_real_escape_string($a_r_cyberdeck['r_deck_firewall']) . "';\n";

      print "document.edit.r_deck_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_deck_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
