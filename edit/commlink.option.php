<?php
# Script: commlink.option.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  if (isset($_SESSION['username'])) {
    $package = "commlink.option.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Updating settings for " . $formVars['id']);

      $q_string  = "select r_link_data,r_link_firewall ";
      $q_string .= "from r_commlink ";
      $q_string .= "where r_link_id = " . $formVars['id'] . " ";
      $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_commlink = mysqli_fetch_array($q_r_commlink);
      mysqli_free_result($q_r_commlink);

# get the value of left
      if (isset($_GET['left'])) {
        $formVars['left'] = clean($_GET['left'], 10);

# Problem: You can't switch a positive value with zero. Shadowrun 6th has a commlink with zeros for Data Processing and Firewall. So if a runner tries to move from Sleaze to Data Processing, it should 
# simply ignore the attempt

# if the left value is:
#
# firewall < data is moving left
#  if firewall, update firewall with data and data with firewall
        if ($formVars['left'] == 'firewall' && $a_r_commlink['r_link_firewall'] != 0 && $a_r_commlink['r_link_data'] != 0) {
          $query = "update r_commlink set r_link_firewall = " . $a_r_commlink['r_link_data']   . ",r_link_data = "   . $a_r_commlink['r_link_firewall'] . " where r_link_id = " . $formVars['id'] . " ";
        }
# data < firewall is moving left
#  if data, update data with firewall and firewall with data
        if ($formVars['left'] == 'data' && $a_r_commlink['r_link_data'] != 0 && $a_r_commlink['r_link_firewall'] != 0) {
          $query = "update r_commlink set r_link_data = "     . $a_r_commlink['r_link_firewall'] . ",r_link_firewall = " . $a_r_commlink['r_link_data']     . " where r_link_id = " . $formVars['id'] . " ";
        }

        if (strlen($query) > 0) {
          $input = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        }
      }

# get the value of right
      if (isset($_GET['right'])) {
        $formVars['right'] = clean($_GET['right'], 10);

# The center is "" if the right value is > 
# data > firewall
#  if firewall, update firewall with data and data with firewall
        if ($formVars['right'] == 'firewall' && $a_r_commlink['r_link_firewall'] != 0 && $a_r_commlink['r_link_data'] != 0) {
          $query = "update r_commlink set r_link_firewall = " . $a_r_commlink['r_link_data']       . ",r_link_data = "       . $a_r_commlink['r_link_firewall'] . " where r_link_id = " . $formVars['id'] . " ";
        }
# firewall > data
#  if attack, update data with firewall and firewall with data
        if ($formVars['right'] == 'data' && $a_r_commlink['r_link_data'] != 0 && $a_r_commlink['r_link_firewall'] != 0) {
          $query = "update r_commlink set r_link_data = "   . $a_r_commlink['r_link_firewall']   . ",r_link_firewall = "   . $a_r_commlink['r_link_data']   . " where r_link_id = " . $formVars['id'] . " ";
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
