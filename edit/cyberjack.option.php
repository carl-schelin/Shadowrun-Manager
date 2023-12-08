<?php
# Script: cyberjack.option.php
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
    $package = "cyberjack.option.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Updating settings for " . $formVars['id']);

      $q_string  = "select r_jack_data,r_jack_firewall ";
      $q_string .= "from r_cyberjack ";
      $q_string .= "where r_jack_id = " . $formVars['id'] . " ";
      $q_r_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack);
      mysql_free_result($q_r_cyberjack);

# get the value of left
      if (isset($_GET['left'])) {
        $formVars['left'] = clean($_GET['left'], 10);

# if the left value is:
#
# firewall < data is moving left
#  if firewall, update firewall with data and data with firewall
        if ($formVars['left'] == 'firewall') {
          $query = "update r_cyberjack set r_jack_firewall = " . $a_r_cyberjack['r_jack_data']   . ",r_jack_data = "   . $a_r_cyberjack['r_jack_firewall'] . " where r_jack_id = " . $formVars['id'] . " ";
        }
# data < firewall is moving left
#  if data, update data with firewall and firewall with data
        if ($formVars['left'] == 'data') {
          $query = "update r_cyberjack set r_jack_data = "     . $a_r_cyberjack['r_jack_firewall'] . ",r_jack_firewall = " . $a_r_cyberjack['r_jack_data']     . " where r_jack_id = " . $formVars['id'] . " ";
        }

        $input = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
      }

# get the value of right
      if (isset($_GET['right'])) {
        $formVars['right'] = clean($_GET['right'], 10);

# The center is "" if the right value is > 
# data > firewall
#  if firewall, update firewall with data and data with firewall
        if ($formVars['right'] == 'firewall') {
          $query = "update r_cyberjack set r_jack_firewall = " . $a_r_cyberjack['r_jack_data']       . ",r_jack_data = "       . $a_r_cyberjack['r_jack_firewall'] . " where r_jack_id = " . $formVars['id'] . " ";
        }
# firewall > attack
#  if attack, update attack with firewall and firewall with attack
        if ($formVars['right'] == 'data') {
          $query = "update r_cyberjack set r_jack_data = "   . $a_r_cyberjack['r_jack_firewall']   . ",r_jack_firewall = "   . $a_r_cyberjack['r_jack_data']   . " where r_jack_id = " . $formVars['id'] . " ";
        }

        $input = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
