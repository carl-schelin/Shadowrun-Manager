<?php
# Script: finance.mysql.php
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
    $package = "finance.mysql.php";
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['fin_character']   = clean($_GET['fin_character'],   10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['fin_character'] == '') {
      $formVars['fin_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($_SESSION['username'], $package, "Building the query.");

        $formVars['fin_id']          = clean($_GET['fin_id'],          10);
        $formVars['fin_funds']       = clean($_GET['fin_funds'],       10);
        $formVars['fin_date']        = clean($_GET['fin_date'],        12);
        $formVars['fin_notes']       = clean($_GET['fin_notes'],     2000);

        if ($formVars['fin_funds'] == '') {
          $formVars['fin_funds'] = 0;
        }

        if ($formVars['fin_date'] == '' || $formVars['fin_date'] == '0000-00-00') {
          $formVars['fin_date'] = date('Y-m-d');
        }

        if (strlen($formVars['fin_notes']) > 0) {

          $q_string =
            "fin_character   =   " . $formVars['fin_character']   . "," . 
            "fin_funds       =   " . $formVars['fin_funds']       . "," . 
            "fin_date        = \"" . $formVars['fin_date']        . "\"," .
            "fin_notes       = \"" . $formVars['fin_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into finance set fin_id = null," . $q_string;
            $message = "Character Nuyen added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update finance set " . $q_string . " where fin_id = " . $formVars['fin_id'];
            $message = "Character Karma updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Notes for: " . $formVars['fin_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');";
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"fin_refresh\" value=\"Refresh Nuyen\" onClick=\"javascript:attach_finance('finance.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"fin_update\"  value=\"Update Nuyen\"  onClick=\"javascript:attach_finance('finance.mysql.php',  1);hideDiv('finance-hide');\">\n";
        $output .= "<input type=\"button\" name=\"fin_add\"     value=\"Add Nuyen\"     onClick=\"javascript:attach_finance('finance.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"fin_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Character Nuyen Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Nuyen:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"fin_funds\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"fin_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">";
        $output .= "<textarea id=\"fin_notes\" name=\"fin_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.fin_notes, document.edit.fin_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.fin_notes, document.edit.fin_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"fin_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('finance_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Character Nuyen Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">+Nuyen</th>\n";
      $output .=   "<th class=\"ui-state-default\">-Nuyen</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $total = 0;
      $nuyen = '&yen;';
      $q_string  = "select fin_id,fin_funds,fin_date,fin_notes ";
      $q_string .= "from finance ";
      $q_string .= "where fin_character = " . $formVars['fin_character'] . " ";
      $q_string .= "order by fin_date desc,fin_id desc ";
      $q_finance = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_finance) > 0) {
        while ($a_finance = mysqli_fetch_array($q_finance)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('finance.fill.php?id=" . $a_finance['fin_id'] . "');showDiv('finance-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_finance('finance.del.php?id="  . $a_finance['fin_id'] . "');\">";
          $linkend   = "</a>";

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
          $output .=   "<td class=\"" . $class . "\" width=\"60\">" . $linkdel                                        . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $positive                                       . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $negative                                       . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $a_finance['fin_date']                          . "</td>\n";
          $output .=   "<td class=\"" . $class . "\">"              . $linkstart . $a_finance['fin_notes'] . $linkend . "</td>\n";
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
        $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . "--"          . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">"              . $positive     . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">"              . $negative     . "</td>\n";
        $output .=   "<td class=\"" . $class . " delete\">"              . date('Y-m-d') . "</td>\n";
        $output .=   "<td class=\"" . $class . "\">"                     . "Total Nuyen" . "</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('finance_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.fin_funds.value = '';\n\n";
      print "document.edit.fin_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.fin_notes.value = '';\n\n";
      print "document.edit.fin_noteLen.value = 2000;\n\n";

      print "document.edit.fin_update.disabled = true;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
