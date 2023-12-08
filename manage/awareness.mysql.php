<?php
# Script: awareness.mysql.php
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
    $package = "awareness.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\" colspan=\"4\">Character Publicity Listing</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\">Publicity Earned</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event</th>\n";
    $output .= "</tr>\n";

    $q_string  = "select pub_id,pub_publicity,pub_date,pub_notes ";
    $q_string .= "from publicity ";
    $q_string .= "where pub_character = " . $formVars['id'] . " ";
    $q_string .= "order by pub_date ";
    $q_publicity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_publicity) > 0) {
      while ($a_publicity = mysqli_fetch_array($q_publicity)) {

        $output .= "<tr>\n";
        $output .=   "<td class=\"ui-widget-content delete\" width=\"120\">" . $a_publicity['pub_publicity'] . "</td>\n";
        $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"  . $a_publicity['pub_date']      . "</td>\n";
        $output .=   "<td class=\"ui-widget-content\">"                      . $a_publicity['pub_notes']     . "</td>\n";
        $output .= "</tr>\n";

      }
    }

    $output .= "</table>\n";

    print "document.getElementById('awareness_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

  }
?>
