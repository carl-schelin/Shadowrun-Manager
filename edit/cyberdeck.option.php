<?php
# Script: cyberdeck.option.php
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
    $package = "cyberdeck.option.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Updating settings for " . $formVars['id']);

      $q_string  = "select r_deck_attack,r_deck_sleaze,r_deck_data,r_deck_firewall ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "where r_deck_id = " . $formVars['id'] . " ";
      $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck);
      mysqli_free_result($q_r_cyberdeck);

# get the value of left
      if (isset($_GET['left'])) {
        $formVars['left'] = clean($_GET['left'], 10);

# Problem: You can't switch a positive value with zero. Shadowrun 6th has a deck with zeros for Data Processing and Firewall. So if a runner tries to move from Sleaze to Data Processing, it should 
# simply ignore the attempt

# if the left value is:
#
# firewall < attack is moving left
#  if firewall, update firewall with attack and attack with firewall
        if ($formVars['left'] == 'firewall' && $a_r_cyberdeck['r_deck_firewall'] != 0 && $a_r_cyberdeck['r_deck_attack'] != 0) {
          $query = "update r_cyberdeck set r_deck_firewall = " . $a_r_cyberdeck['r_deck_attack']   . ",r_deck_attack = "   . $a_r_cyberdeck['r_deck_firewall'] . " where r_deck_id = " . $formVars['id'] . " ";
        }
# attack < sleaze is moving left
#  if attack, update attack with sleaze and sleaze with attack
        if ($formVars['left'] == 'attack' && $a_r_cyberdeck['r_deck_attack'] != 0 && $a_r_cyberdeck['r_deck_sleaze'] != 0) {
          $query = "update r_cyberdeck set r_deck_attack = "   . $a_r_cyberdeck['r_deck_sleaze']   . ",r_deck_sleaze = "   . $a_r_cyberdeck['r_deck_attack']   . " where r_deck_id = " . $formVars['id'] . " ";
        }
# sleaze < data is moving left
#  if sleaze, update sleaze with data and data with sleaze
        if ($formVars['left'] == 'sleaze' && $a_r_cyberdeck['r_deck_sleaze'] != 0 && $a_r_cyberdeck['r_deck_data'] != 0) {
          $query = "update r_cyberdeck set r_deck_sleaze = "   . $a_r_cyberdeck['r_deck_data']     . ",r_deck_data = "     . $a_r_cyberdeck['r_deck_sleaze']   . " where r_deck_id = " . $formVars['id'] . " ";
        }
# data < firewall is moving left
#  if data, update data with firewall and firewall with data
        if ($formVars['left'] == 'data' && $a_r_cyberdeck['r_deck_data'] != 0 && $a_r_cyberdeck['r_deck_firewall'] != 0) {
          $query = "update r_cyberdeck set r_deck_data = "     . $a_r_cyberdeck['r_deck_firewall'] . ",r_deck_firewall = " . $a_r_cyberdeck['r_deck_data']     . " where r_deck_id = " . $formVars['id'] . " ";
        }

        if (strlen($query) > 0) {
          $input = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        }
      }

# get the value of right
      if (isset($_GET['right'])) {
        $formVars['right'] = clean($_GET['right'], 10);

# The center is "" if the right value is > 
# attack > sleaze
#  if sleaze, update sleaze with attack and attack with sleaze
        if ($formVars['right'] == 'sleaze' && $a_r_cyberdeck['r_deck_sleaze'] != 0 && $a_r_cyberdeck['r_deck_attack'] != 0) {
          $query = "update r_cyberdeck set r_deck_sleaze = "   . $a_r_cyberdeck['r_deck_attack']     . ",r_deck_attack = "     . $a_r_cyberdeck['r_deck_sleaze']   . " where r_deck_id = " . $formVars['id'] . " ";
        }
# sleaze > data
#  if data, update data with sleaze and sleaze with data
        if ($formVars['right'] == 'data' && $a_r_cyberdeck['r_deck_data'] != 0 && $a_r_cyberdeck['r_deck_sleaze'] != 0) {
          $query = "update r_cyberdeck set r_deck_data = "     . $a_r_cyberdeck['r_deck_sleaze']     . ",r_deck_sleaze = "     . $a_r_cyberdeck['r_deck_data']     . " where r_deck_id = " . $formVars['id'] . " ";
        }
# data > firewall
#  if firewall, update firewall with data and data with firewall
        if ($formVars['right'] == 'firewall' && $a_r_cyberdeck['r_deck_firewall'] != 0 && $a_r_cyberdeck['r_deck_data'] != 0) {
          $query = "update r_cyberdeck set r_deck_firewall = " . $a_r_cyberdeck['r_deck_data']       . ",r_deck_data = "       . $a_r_cyberdeck['r_deck_firewall'] . " where r_deck_id = " . $formVars['id'] . " ";
        }
# firewall > attack
#  if attack, update attack with firewall and firewall with attack
        if ($formVars['right'] == 'attack' && $a_r_cyberdeck['r_deck_attack'] != 0 && $a_r_cyberdeck['r_deck_firewall'] != 0) {
          $query = "update r_cyberdeck set r_deck_attack = "   . $a_r_cyberdeck['r_deck_firewall']   . ",r_deck_firewall = "   . $a_r_cyberdeck['r_deck_attack']   . " where r_deck_id = " . $formVars['id'] . " ";
        }

        if (strlen($query) > 0) {
          $input = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        }
      }

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
