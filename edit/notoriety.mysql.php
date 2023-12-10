<?php
# Script: notoriety.mysql.php
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
    $package = "notoriety.mysql.php";
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['not_character']   = clean($_GET['not_character'],   10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['not_character'] == '') {
      $formVars['not_character'] = 0;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($db, $_SESSION['username'], $package, "Building the query.");

        $formVars['not_id']          = clean($_GET['not_id'],          10);
        $formVars['not_notoriety']   = clean($_GET['not_notoriety'],   10);
        $formVars['not_date']        = clean($_GET['not_date'],        12);
        $formVars['not_notes']       = clean($_GET['not_notes'],     2000);

        if ($formVars['not_notoriety'] == '') {
          $formVars['not_notoriety'] = 0;
        }

        if ($formVars['not_date'] == '' || $formVars['not_date'] == '0000-00-00') {
          $formVars['not_date'] = date('Y-m-d');
        }

        if (strlen($formVars['not_notes']) > 0) {

          $q_string =
            "not_character   =   " . $formVars['not_character']   . "," . 
            "not_notoriety   =   " . $formVars['not_notoriety']   . "," . 
            "not_date        = \"" . $formVars['not_date']        . "\"," .
            "not_notes       = \"" . $formVars['not_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into notoriety set not_id = null," . $q_string;
            $message = "Character Notoriety added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update notoriety set " . $q_string . " where not_id = " . $formVars['not_id'];
            $message = "Character Notoriety updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Notes for: " . $formVars['not_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');";
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"not_refresh\" value=\"Refresh Notoriety\" onClick=\"javascript:attach_notoriety('notoriety.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"not_update\"  value=\"Update Notoriety\"  onClick=\"javascript:attach_notoriety('notoriety.mysql.php',  1);hideDiv('notoriety-hide');\">\n";
        $output .= "<input type=\"button\" name=\"not_add\"     value=\"Add Notoriety\"     onClick=\"javascript:attach_notoriety('notoriety.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"not_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Character Notoriety Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Notoriety Earned:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"not_notoriety\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"not_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">";
        $output .= "<textarea id=\"not_notes\" name=\"not_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.not_notes, document.edit.not_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.not_notes, document.edit.not_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"not_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('notoriety_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"4\">Character Notoriety Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Notoriety Earned</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select not_id,not_notoriety,not_date,not_notes ";
      $q_string .= "from notoriety ";
      $q_string .= "where not_character = " . $formVars['not_character'] . " ";
      $q_string .= "order by not_date ";
      $q_notoriety = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_notoriety) > 0) {
        while ($a_notoriety = mysqli_fetch_array($q_notoriety)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('notoriety.fill.php?id=" . $a_notoriety['not_id'] . "');showDiv('notoriety-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_notoriety('notoriety.del.php?id="  . $a_notoriety['not_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                      . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"120\">"       . $a_notoriety['not_notoriety']            . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"      . $a_notoriety['not_date']             . "</td>\n";
          $output .=   "<td class=\"ui-widget-content\">"              . $linkstart . $a_notoriety['not_notes'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      }

      $output .= "</table>\n";

      print "document.getElementById('notoriety_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.edit.not_notoriety.value = '';\n\n";
      print "document.edit.not_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.not_notes.value = '';\n\n";
      print "document.edit.not_noteLen.value = 2000;\n\n";

      print "document.edit.not_update.disabled = true;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
