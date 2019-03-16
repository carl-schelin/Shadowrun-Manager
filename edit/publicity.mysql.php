<?php
# Script: publicity.mysql.php
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
    $package = "publicity.mysql.php";
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['pub_character']   = clean($_GET['pub_character'],   10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['pub_character'] == '') {
      $formVars['pub_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($_SESSION['username'], $package, "Building the query.");

        $formVars['pub_id']          = clean($_GET['pub_id'],          10);
        $formVars['pub_publicity']   = clean($_GET['pub_publicity'],   10);
        $formVars['pub_date']        = clean($_GET['pub_date'],        12);
        $formVars['pub_notes']       = clean($_GET['pub_notes'],     2000);

        if ($formVars['pub_publicity'] == '') {
          $formVars['pub_publicity'] = 0;
        }

        if ($formVars['pub_date'] == '' || $formVars['pub_date'] == '0000-00-00') {
          $formVars['pub_date'] = date('Y-m-d');
        }

        if (strlen($formVars['pub_notes']) > 0) {

          $q_string =
            "pub_character   =   " . $formVars['pub_character']   . "," . 
            "pub_publicity   =   " . $formVars['pub_publicity']   . "," . 
            "pub_date        = \"" . $formVars['pub_date']        . "\"," .
            "pub_notes       = \"" . $formVars['pub_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into publicity set pub_id = null," . $q_string;
            $message = "Character Publicity added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update publicity set " . $q_string . " where pub_id = " . $formVars['pub_id'];
            $message = "Character Publicity updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Notes for: " . $formVars['pub_id']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');";
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"pub_refresh\" value=\"Refresh Publicity\" onClick=\"javascript:attach_publicity('publicity.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"pub_update\"  value=\"Update Publicity\"  onClick=\"javascript:attach_publicity('publicity.mysql.php',  1);hideDiv('publicity-hide');\">\n";
        $output .= "<input type=\"button\" name=\"pub_add\"     value=\"Add Publicity\"     onClick=\"javascript:attach_publicity('publicity.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"pub_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Character Publicity Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Publicity Earned:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"pub_publicity\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"pub_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">";
        $output .= "<textarea id=\"pub_notes\" name=\"pub_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.pub_notes, document.edit.pub_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.pub_notes, document.edit.pub_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"pub_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('publicity_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"4\">Character Publicity Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Publicity Earned</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pub_id,pub_publicity,pub_date,pub_notes ";
      $q_string .= "from publicity ";
      $q_string .= "where pub_character = " . $formVars['pub_character'] . " ";
      $q_string .= "order by pub_date ";
      $q_publicity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_publicity) > 0) {
        while ($a_publicity = mysql_fetch_array($q_publicity)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('publicity.fill.php?id=" . $a_publicity['pub_id'] . "');showDiv('publicity-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_publicity('publicity.del.php?id="  . $a_publicity['pub_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                      . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"120\">"       . $a_publicity['pub_publicity']            . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"      . $a_publicity['pub_date']             . "</td>\n";
          $output .=   "<td class=\"ui-widget-content\">"              . $linkstart . $a_publicity['pub_notes'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      }

      $output .= "</table>\n";

      print "document.getElementById('publicity_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.pub_publicity.value = '';\n\n";
      print "document.edit.pub_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.pub_notes.value = '';\n\n";
      print "document.edit.pub_noteLen.value = 2000;\n\n";

      print "document.edit.pub_update.disabled = true;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
