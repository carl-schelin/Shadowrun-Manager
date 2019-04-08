<?php
# Script: condition.checked.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "inventory.detail.php";
    $formVars['id']              = clean($_GET['id'],              10);      # the character or item id, being modified
    $formVars['cond_id']         = clean($_GET['cond_id'],         10);      # what checkbox was checked
    $formVars['cond_function']   = clean($_GET['cond_function'],   10);      # which condition monitor

    if (check_userlevel($AL_Shadowrunner)) {

# need to check and make sure it's the character owner or higher; runner, fixer, or johnson

# checkboxes. change physical conditions
      if ($formVars['cond_function'] == 'physical') {
        $q_string  = "select runr_body,runr_physicalcon ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_runners = mysql_fetch_array($q_runners);

        $physical_damage = ceil(($a_runners['runr_body'] / 2) + 8);

# if the passed value is the same as the existing value, it should be unchecked and not checked to that spot
        if ($a_runners['runr_physicalcon'] == $formVars['cond_id']) {
          $formVars['cond_id']--;
        }

        $q_string  = "update ";
        $q_string .= "runners ";
        $q_string .= "set ";
        $q_string .= "runr_physicalcon = " . $formVars['cond_id'] . " ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

        for ($i = 1; $i <= 18; $i++) {
          if ($physical_damage >= $i) {
            $checked = 'false';
            if ($i <= $formVars['cond_id']) {
              $checked = 'true';
            }

            print "document.getElementById('physcon" . $i . "').checked = " . $checked . ";\n";
          }
        }
      }

# checkboxes. change stun conditions
      if ($formVars['cond_function'] == 'stun') {
        $q_string  = "select runr_willpower,runr_stuncon ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_runners = mysql_fetch_array($q_runners);

        $stun_damage = ceil(($a_runners['runr_willpower'] / 2) + 8);

# if the passed value is the same as the existing value, it should be unchecked and not checked to that spot
        if ($a_runners['runr_stuncon'] == $formVars['cond_id']) {
          $formVars['cond_id']--;
        }

        $q_string  = "update ";
        $q_string .= "runners ";
        $q_string .= "set ";
        $q_string .= "runr_stuncon = " . $formVars['cond_id'] . " ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

        for ($i = 1; $i <= 12; $i++) {
          if ($stun_damage >= $i) {
            $checked = 'false';
            if ($i <= $formVars['cond_id']) {
              $checked = 'true';
            }

            print "document.getElementById('stuncon" . $i . "').checked = " . $checked . ";\n";
          }
        }
      }

# checkboxes. change commlink conditions
      if ($formVars['cond_function'] == 'commlink') {
        $q_string  = "select r_link_conmon,link_rating ";
        $q_string .= "from r_commlink ";
        $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
        $q_string .= "where r_link_id = " . $formVars['id'] . " ";
        $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_r_commlink = mysql_fetch_array($q_r_commlink);

        $matrix_damage = ceil(($a_r_commlink['link_rating'] / 2) + 8);

# if the passed value is the same as the existing value, it should be unchecked and not checked to that spot
        if ($a_r_commlink['r_link_conmon'] == $formVars['cond_id']) {
          $formVars['cond_id']--;
        }

        $q_string  = "update ";
        $q_string .= "r_commlink ";
        $q_string .= "set ";
        $q_string .= "r_link_conmon = " . $formVars['cond_id'] . " ";
        $q_string .= "where r_link_id = " . $formVars['id'] . " ";
        $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

        for ($i = 1; $i <= 18; $i++) {
          if ($matrix_damage >= $i) {
            $checked = 'false';
            if ($i <= $formVars['cond_id']) {
              $checked = 'true';
            }

            print "document.getElementById('linkcon" . $i . "').checked = " . $checked . ";\n";
          }
        }
      }

# checkboxes. change cyberdeck conditions
      if ($formVars['cond_function'] == 'cyberdeck') {
        $q_string  = "select r_deck_conmon,deck_rating ";
        $q_string .= "from r_cyberdeck ";
        $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
        $q_string .= "where r_deck_id = " . $formVars['id'] . " ";
        $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_r_cyberdeck = mysql_fetch_array($q_r_cyberdeck);

        $matrix_damage = ceil(($a_r_cyberdeck['deck_rating'] / 2) + 8);

# if the passed value is the same as the existing value, it should be unchecked and not checked to that spot
        if ($a_r_cyberdeck['r_deck_conmon'] == $formVars['cond_id']) {
          $formVars['cond_id']--;
        }

        $q_string  = "update ";
        $q_string .= "r_cyberdeck ";
        $q_string .= "set ";
        $q_string .= "r_deck_conmon = " . $formVars['cond_id'] . " ";
        $q_string .= "where r_deck_id = " . $formVars['id'] . " ";
        $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

        for ($i = 1; $i <= 18; $i++) {
          if ($matrix_damage >= $i) {
            $checked = 'false';
            if ($i <= $formVars['cond_id']) {
              $checked = 'true';
            }

            print "document.getElementById('deckcon" . $i . "').checked = " . $checked . ";\n";
          }
        }
      }

    } else {
      logaccess($_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
