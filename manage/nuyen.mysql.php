<?php
# Script: nuyen.mysql.php
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
    $package = "nuyen.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Character Nuyen Listing</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .=   "<th class=\"ui-state-default\">+Nuyen</th>\n";
    $output .=   "<th class=\"ui-state-default\">-Nuyen</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
    $output .=   "<th class=\"ui-state-default\">Event</th>\n";
    $output .= "</tr>\n";

    $total = 0;
    $nuyen = '&yen;';
    $q_string  = "select fin_id,fin_funds,fin_date,fin_notes ";
    $q_string .= "from finance ";
    $q_string .= "where fin_character = " . $formVars['id'] . " ";
    $q_string .= "order by fin_date desc,fin_id desc ";
    $q_finance = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_finance) > 0) {
      while ($a_finance = mysql_fetch_array($q_finance)) {

        $total += $a_finance['fin_funds'];

        if ($a_finance['fin_funds'] < 0) {
          $negative = number_format($a_finance['fin_funds'], 0, ".", ",") . $nuyen;
          $positive = '';
        } else {
          $negative = '';
          $positive = number_format($a_finance['fin_funds'], 0, ".", ",") . $nuyen;
        }

        $class = "ui-widget-content";
        if ($a_finance['fin_funds'] < 0) {
          $class = "ui-state-error";
        }

        $output .= "<tr>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $positive               . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $negative               . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">" . $a_finance['fin_date']  . "</td>\n";
        $output .=   "<td class=\"" . $class . "\">"        . $a_finance['fin_notes'] . "</td>\n";
        $output .= "</tr>\n";

      }
      if ($total < 0) {
        $negative = number_format($total, 0, ".", ",") . $nuyen;
        $positive = '';
      } else {
        $negative = '';
        $positive = number_format($total, 0, ".", ",") . $nuyen;
      }

      $class = "ui-widget-content";
      if ($total < 0) {
        $class = "ui-state-error";
      }

      $output .= "<tr>\n";
      $output .=   "<td class=\"" . $class . " delete\">" . $positive     . "</td>\n";
      $output .=   "<td class=\"" . $class . " delete\">" . $negative     . "</td>\n";
      $output .=   "<td class=\"" . $class . " delete\">" . date('Y-m-d') . "</td>\n";
      $output .=   "<td class=\"" . $class . "\">"        . "Total Nuyen" . "</td>\n";
      $output .= "</tr>\n";
    }

    $output .= "</table>\n";

    print "document.getElementById('nuyen_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

  }
?>
