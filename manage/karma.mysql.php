<?php
# Script: karma.mysql.php
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
    $package = "karma.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Character Karma Listing</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\">+Karma</th>\n";
    $output .=   "<th class=\"ui-state-default\">-Karma</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event</th>\n";
    $output .= "</tr>\n";

    $q_string  = "select kar_id,kar_karma,kar_date,kar_notes ";
    $q_string .= "from karma ";
    $q_string .= "where kar_character = " . $formVars['id'] . " ";
    $q_string .= "order by kar_date ";
    $q_karma = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_karma) > 0) {
      while ($a_karma = mysqli_fetch_array($q_karma)) {

        if ($a_karma['kar_karma'] < 0) {
          $class = "ui-state-error";
          $negative = $a_karma['kar_karma'];
          $positive = '';
        } else {
          $class = "ui-widget-content";
          $negative = '';
          $positive = $a_karma['kar_karma'];
        }

        $output .= "<tr>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $positive             . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $negative             . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $a_karma['kar_date']  . "</td>\n";
        $output .=   "<td class=\"" . $class . "\">"        . $a_karma['kar_notes'] . "</td>\n";
        $output .= "</tr>\n";

      }
    }

    $output .= "</table>\n";

    print "document.getElementById('karma_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

  }
?>
