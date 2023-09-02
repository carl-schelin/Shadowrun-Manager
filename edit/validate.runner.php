<?php
# Script: validate.runner.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  $formVars['id']           = clean($_GET['id'],            10);
  $formVars['runr_name']    = clean($_GET['runr_name'],     60);
  $formVars['runr_version'] = clean($_GET['runr_version'],  10);

# search for the name in the database of runners

  $q_string  = "select runr_id ";
  $q_string .= "from runners ";
  $q_string .= "where runr_name = \"" . $formVars['runr_name'] . "\" and runr_version = " . $formVars['runr_version'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

# if a runner record is found for the same edition, make sure we don't overwrite 
# an existing runner that's not the one we're working on. If the ID passed is the 
# same as the ID found, then update button is enabled, add button is disabled.

  if (mysql_num_rows($q_runners) > 0) {
    $a_runners = mysql_fetch_array($q_runners);

    if ($formVars['id'] == $a_runners['runr_id']) {
?>
      document.edit.update.disabled = false;
      document.edit.addnew.disabled = true;
      document.getElementById('runner_name').textContent = '';
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('edit_runner').className = "ui-widget-content";
      } else {
        document.getElementById('edit_runner').setAttribute("class","ui-widget-content");
      }
<?php
    } else {
?>
      document.edit.update.disabled = true;
      document.edit.addnew.disabled = true;
      document.getElementById('runner_name').textContent = 'Runner Exists';
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('edit_runner').className = "ui-state-highlight";
      } else {
        document.getElementById('edit_runner').setAttribute("class","ui-state-highlight");
      }
<?php
    }
  } else {
# we want to be able to rename a runner just in case so don't disable update
?>
    document.edit.update.disabled = false;
    document.edit.addnew.disabled = false;
    document.getElementById('runner_name').textContent = 'New Runner';
    if (navigator.appName == "Microsoft Internet Explorer") {
      document.getElementById('edit_runner').className = "ui-widget-content";
    } else {
      document.getElementById('edit_runner').setAttribute("class","ui-widget-content");
    }
<?php
  }
?>
