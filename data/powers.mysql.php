<?php
# Script: powers.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: This is used for spirits to add or modify the power descriptions

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "powers.mysql.php";
    $formVars['update']               = clean($_GET['update'],        10);

    $formVars['sp_power_creature'] = '';
    if (isset($_GET['r_spirit_id'])) {
      $formVars['sp_power_creature']    = clean($_GET['r_spirit_id'],   10);
    }

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['sp_power_creature'] == '') {
      $formVars['sp_power_creature'] = 0;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                     = clean($_GET['id'],                      10);
        $formVars['sp_power_number']        = clean($_GET['pow_id'],                  10);
        $formVars['sp_power_optional']      = clean($_GET['optional'],                 5);
        $formVars['sp_power_specialize']    = clean($_GET['sp_power_specialize'],    255);

        if ($formVars['sp_power_number'] == '') {
          $formVars['sp_power_number'] = 0;
        }
        if ($formVars['sp_power_optional'] == 'Yes') {
          $formVars['sp_power_optional'] = 1;
        } else {
          $formVars['sp_power_optional'] = 0;
        }

        if ($formVars['sp_power_number'] > 0 || $formVars['id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          if ($formVars['update'] == 0) {
            $q_string = 
              "sp_power_creature    =   " . $formVars['sp_power_creature']  . "," .
              "sp_power_number      =   " . $formVars['sp_power_number']    . "," .
              "sp_power_optional    =   " . $formVars['sp_power_optional'];

            $query = "insert into sp_powers set sp_power_id = NULL, " . $q_string;
          }

          if ($formVars['update'] == 1) {
            $q_string = 
              "sp_power_specialize    = \"" . $formVars['sp_power_specialize']  . "\"";

            $query = "update sp_powers set " . $q_string . " where sp_power_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['sp_power_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.(" . $formVars['id'] . ")');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Power Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('power-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"power-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Metatype Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Metatype from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Metatype to toggle the form and edit the Metatype.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Metatype Management</strong> title bar to toggle the <strong>Metatype Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Range</th>\n";
      $output .=   "<th class=\"ui-state-default\">Action</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      if ($formVars['sp_power_creature'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Select one of your Spirits in order to select a Power.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      } else {
        $q_string  = "select pow_id,pow_name,pow_type,pow_range,pow_action,pow_duration,pow_description,ver_book,pow_page ";
        $q_string .= "from powers ";
        $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by pow_name,ver_version ";
        $q_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_powers) > 0) {
          while ($a_powers = mysqli_fetch_array($q_powers)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:attach_power('powers.mysql.php?optional=No&pow_id=" . $a_powers['pow_id'] . "', 0);\">";
            $linkend = "</a>";

            $power_book = return_Book($a_powers['ver_book'], $a_powers['pow_page']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_powers['pow_id']                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_powers['pow_name']        . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_type']                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_range']                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_action']                 . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_duration']               . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                     . $a_powers['pow_description']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $power_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
      }

      print "document.getElementById('powers_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";



      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Optional Power Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('optional-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"optional-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Metatype Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Metatype from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Metatype to toggle the form and edit the Metatype.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Metatype Management</strong> title bar to toggle the <strong>Metatype Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Range</th>\n";
      $output .=   "<th class=\"ui-state-default\">Action</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      if ($formVars['sp_power_creature'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Select one of your Spirits in order to select an Optional Power.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      } else {
        $q_string  = "select pow_id,pow_name,pow_type,pow_range,pow_action,pow_duration,pow_description,ver_book,pow_page ";
        $q_string .= "from powers ";
        $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by pow_name,ver_version ";
        $q_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_powers) > 0) {
          while ($a_powers = mysqli_fetch_array($q_powers)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:attach_power('powers.mysql.php?optional=Yes&pow_id=" . $a_powers['pow_id'] . "', 0);\">";
            $linkend = "</a>";

            $power_book = return_Book($a_powers['ver_book'], $a_powers['pow_page']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_powers['pow_id']                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_powers['pow_name']        . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_type']                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_range']                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_action']                 . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_powers['pow_duration']               . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                     . $a_powers['pow_description']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $power_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
      }

      print "document.getElementById('optional_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
