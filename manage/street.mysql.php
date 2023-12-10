<?php
# Script: street.mysql.php
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
    $package = "street.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\" colspan=\"4\">Character Street Cred Listing</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\">Street Cred Awarded</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event</th>\n";
    $output .= "</tr>\n";

    $q_string  = "select st_id,st_cred,st_date,st_notes ";
    $q_string .= "from street ";
    $q_string .= "where st_character = " . $formVars['id'] . " ";
    $q_string .= "order by st_date ";
    $q_street = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_street) > 0) {
      while ($a_street = mysqli_fetch_array($q_street)) {

        $output .= "<tr>\n";
        $output .=   "<td class=\"ui-widget-content delete\" width=\"120\">" . $a_street['st_cred']  . "</td>\n";
        $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"  . $a_street['st_date']  . "</td>\n";
        $output .=   "<td class=\"ui-widget-content\">"                      . $a_street['st_notes'] . "</td>\n";
        $output .= "</tr>\n";

      }
    }

    $output .= "</table>\n";

    print "document.getElementById('street_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

  }
?>
