<?php
# Script: history.mysql.php
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
    $package = "history.mysql.php";
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['his_character']   = clean($_GET['his_character'],   10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['his_character'] == '') {
      $formVars['his_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($_SESSION['username'], $package, "Building the query.");

        $formVars['his_id']          = clean($_GET['his_id'],          10);
        $formVars['his_date']        = clean($_GET['his_date'],        12);
        $formVars['his_notes']       = clean($_GET['his_notes'],     2000);

        if ($formVars['his_date'] == '' || $formVars['his_date'] == '0000-00-00') {
          $formVars['his_date'] = date('Y-m-d');
        }

        if (strlen($formVars['his_notes']) > 0) {

          $q_string =
            "his_character   =   " . $formVars['his_character']   . "," . 
            "his_date        = \"" . $formVars['his_date']        . "\"," .
            "his_notes       = \"" . $formVars['his_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into history set his_id = null," . $q_string;
          }

          if ($formVars['update'] == 1) {
            $query = "update history set " . $q_string . " where his_id = " . $formVars['his_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Notes for: " . $formVars['his_id']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"his_refresh\" value=\"Refresh History\" onClick=\"javascript:attach_history('history.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"his_update\"  value=\"Update History\"  onClick=\"javascript:attach_history('history.mysql.php',  1);hideDiv('history-hide');\">\n";
        $output .= "<input type=\"button\" name=\"his_add\"     value=\"Add History\"     onClick=\"javascript:attach_history('history.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"his_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Character History Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"his_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\">";
        $output .= "<textarea id=\"his_notes\" name=\"his_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.his_notes, document.edit.his_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.his_notes, document.edit.his_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"his_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('history_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"3\">Character History Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select his_id,his_date,his_notes ";
      $q_string .= "from history ";
      $q_string .= "where his_character = " . $formVars['his_character'] . " ";
      $q_string .= "order by his_date ";
      $q_history = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_history) > 0) {
        while ($a_history = mysqli_fetch_array($q_history)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('history.fill.php?id=" . $a_history['his_id'] . "');showDiv('history-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_history('history.del.php?id="  . $a_history['his_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                        . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"       . $a_history['his_date']             . "</td>\n";
          $output .=   "<td class=\"ui-widget-content\">"              . $linkstart . $a_history['his_notes'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      }

      $output .= "</table>\n";

      print "document.getElementById('history_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.his_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.his_notes.value = '';\n\n";
      print "document.edit.his_noteLen.value = 2000;\n\n";

      print "document.edit.his_update.disabled = true;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
