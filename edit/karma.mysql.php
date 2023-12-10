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
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['kar_character']   = clean($_GET['kar_character'],   10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['kar_character'] == '') {
      $formVars['kar_character'] = 0;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($db, $_SESSION['username'], $package, "Building the query.");

        $formVars['kar_id']          = clean($_GET['kar_id'],          10);
        $formVars['kar_karma']       = clean($_GET['kar_karma'],       10);
        $formVars['kar_date']        = clean($_GET['kar_date'],        12);
        $formVars['kar_notes']       = clean($_GET['kar_notes'],     2000);

        if ($formVars['kar_karma'] == '') {
          $formVars['kar_karma'] = 0;
        }

        if ($formVars['kar_date'] == '' || $formVars['kar_date'] == '0000-00-00') {
          $formVars['kar_date'] = date('Y-m-d');
        }

        if (strlen($formVars['kar_notes']) > 0) {

          $q_string =
            "kar_character   =   " . $formVars['kar_character']   . "," . 
            "kar_karma       =   " . $formVars['kar_karma']       . "," . 
            "kar_date        = \"" . $formVars['kar_date']        . "\"," .
            "kar_notes       = \"" . $formVars['kar_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into karma set kar_id = null," . $q_string;
            $message = "Character Karma added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update karma set " . $q_string . " where kar_id = " . $formVars['kar_id'];
            $message = "Character Karma updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Notes for: " . $formVars['kar_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');";
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"kar_refresh\" value=\"Refresh Karma\" onClick=\"javascript:attach_karma('karma.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"kar_update\"  value=\"Update Karma\"  onClick=\"javascript:attach_karma('karma.mysql.php',  1);hideDiv('karma-hide');\">\n";
        $output .= "<input type=\"button\" name=\"kar_add\"     value=\"Add Karma\"     onClick=\"javascript:attach_karma('karma.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"kar_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Character Karma Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Karma Awarded:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"kar_karma\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"kar_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">";
        $output .= "<textarea id=\"kar_notes\" name=\"kar_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.kar_notes, document.edit.kar_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.kar_notes, document.edit.kar_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"kar_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('karma_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Character Karma Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">+Karma</th>\n";
      $output .=   "<th class=\"ui-state-default\">-Karma</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select kar_id,kar_karma,kar_date,kar_notes ";
      $q_string .= "from karma ";
      $q_string .= "where kar_character = " . $formVars['kar_character'] . " ";
      $q_string .= "order by kar_date ";
      $q_karma = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_karma) > 0) {
        while ($a_karma = mysqli_fetch_array($q_karma)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('karma.fill.php?id=" . $a_karma['kar_id'] . "');showDiv('karma-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_karma('karma.del.php?id="  . $a_karma['kar_id'] . "');\">";
          $linkend   = "</a>";

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
          $output .=   "<td class=\"" . $class . "\" width=\"60\">" . $linkdel                                      . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $positive                                     . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $negative                                     . "</td>\n";
          $output .=   "<td class=\"" . $class . " delete\">"       . $a_karma['kar_date']                          . "</td>\n";
          $output .=   "<td class=\"" . $class . "\">"              . $linkstart . $a_karma['kar_notes'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      }

      $output .= "</table>\n";

      print "document.getElementById('karma_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.edit.kar_karma.value = '';\n\n";
      print "document.edit.kar_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.kar_notes.value = '';\n\n";
      print "document.edit.kar_noteLen.value = 2000;\n\n";

      print "document.edit.kar_update.disabled = true;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
