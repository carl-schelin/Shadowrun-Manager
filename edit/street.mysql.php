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
    $formVars['update']          = clean($_GET['update'],          10);
    $formVars['st_character']    = clean($_GET['st_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['st_character'] == '') {
      $formVars['st_character'] = 0;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        logaccess($db, $_SESSION['username'], $package, "Building the query.");

        $formVars['st_id']          = clean($_GET['st_id'],          10);
        $formVars['st_cred']        = clean($_GET['st_cred'],        10);
        $formVars['st_date']        = clean($_GET['st_date'],        12);
        $formVars['st_notes']       = clean($_GET['st_notes'],     2000);

        if ($formVars['st_cred'] == '') {
          $formVars['st_cred'] = 0;
        }

        if ($formVars['st_date'] == '' || $formVars['st_date'] == '0000-00-00') {
          $formVars['st_date'] = date('Y-m-d');
        }

        if (strlen($formVars['st_notes']) > 0) {

          $q_string =
            "st_character   =   " . $formVars['st_character']   . "," . 
            "st_cred        =   " . $formVars['st_cred']        . "," . 
            "st_date        = \"" . $formVars['st_date']        . "\"," .
            "st_notes       = \"" . $formVars['st_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into street set st_id = null," . $q_string;
            $message = "Character Street Cred added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update street set " . $q_string . " where st_id = " . $formVars['st_id'];
            $message = "Character Street Cred updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Notes for: " . $formVars['st_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');";
        }

      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"st_refresh\" value=\"Refresh Street Cred\" onClick=\"javascript:attach_street('street.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"st_update\"  value=\"Update Street Cred\"  onClick=\"javascript:attach_street('street.mysql.php',  1);hideDiv('street-hide');\">\n";
        $output .= "<input type=\"button\" name=\"st_add\"     value=\"Add Street Cred\"     onClick=\"javascript:attach_street('street.mysql.php',  0);\">\n";
        $output .= "<input type=\"hidden\" name=\"st_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Character Street Cred Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Street Cred Awarded:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"st_cred\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Event Date:</td>\n";
        $output .= "  <td class=\"ui-widget-content\"><input type=\"text\" name=\"st_date\" value=\"" . date('Y-m-d') . "\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Event:</td>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">";
        $output .= "<textarea id=\"st_notes\" name=\"st_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.st_notes, document.edit.st_noteLen, 2000);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.st_notes, document.edit.st_noteLen, 2000);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"st_noteLen\" size=\"5\" value=\"2000\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('street_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"4\">Character Street Cred Listing</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Street Cred Awarded</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Event</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select st_id,st_cred,st_date,st_notes ";
      $q_string .= "from street ";
      $q_string .= "where st_character = " . $formVars['st_character'] . " ";
      $q_string .= "order by st_date ";
      $q_street = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_street) > 0) {
        while ($a_street = mysqli_fetch_array($q_street)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('street.fill.php?id=" . $a_street['st_id'] . "');showDiv('street-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_street('street.del.php?id="  . $a_street['st_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                      . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"120\">"       . $a_street['st_cred']            . "</td>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"80\">"      . $a_street['st_date']             . "</td>\n";
          $output .=   "<td class=\"ui-widget-content\">"              . $linkstart . $a_street['st_notes'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      }

      $output .= "</table>\n";

      print "document.getElementById('street_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.edit.st_cred.value = '';\n\n";
      print "document.edit.st_date.value = '" . date('Y-m-d') . "';\n\n";
      print "document.edit.st_notes.value = '';\n\n";
      print "document.edit.st_noteLen.value = 2000;\n\n";

      print "document.edit.st_update.disabled = true;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
