<?php
# Script: history.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "history.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

      $q_string  = "select runr_desc,runr_sop,runr_available ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_runners = mysqli_fetch_array($q_runners);

      if ($a_runners['runr_available']) {
        $not = '';
      } else {
        $not = 'not ';
      }

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Character Availability</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Character <strong>is " . $not . "</strong>available to Referees</td>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Character Description</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">" . $a_runners['runr_desc'] . "</td>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Character Standard Operating Procedure</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">" . $a_runners['runr_sop'] . "</td>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";
    }
  }
?>

document.getElementById('character_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

