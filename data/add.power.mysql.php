<?php
# Script: add.power.mysql.php
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
    $package = "add.power.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],               10);
        $formVars['pow_name']          = clean($_GET['pow_name'],         60);
        $formVars['pow_type']          = clean($_GET['pow_type'],         10);
        $formVars['pow_range']         = clean($_GET['pow_range'],        10);
        $formVars['pow_action']        = clean($_GET['pow_action'],       10);
        $formVars['pow_duration']      = clean($_GET['pow_duration'],     10);
        $formVars['pow_description']   = clean($_GET['pow_description'], 128);
        $formVars['pow_book']          = clean($_GET['pow_book'],         10);
        $formVars['pow_page']          = clean($_GET['pow_page'],         10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['pow_page'] == '') {
          $formVars['pow_page'] = 0;
        }

        if (strlen($formVars['pow_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "pow_name             = \"" . $formVars['pow_name']          . "\"," .
            "pow_type             = \"" . $formVars['pow_type']          . "\"," .
            "pow_range            = \"" . $formVars['pow_range']         . "\"," .
            "pow_action           = \"" . $formVars['pow_action']        . "\"," .
            "pow_duration         = \"" . $formVars['pow_duration']      . "\"," .
            "pow_description      = \"" . $formVars['pow_description']   . "\"," .
            "pow_book             =   " . $formVars['pow_book']          . "," .
            "pow_page             =   " . $formVars['pow_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into powers set pow_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update powers set " . $q_string . " where pow_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['pow_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Range</th>\n";
      $output .=   "<th class=\"ui-state-default\">Action</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pow_id,pow_name,pow_type,pow_range,pow_action,pow_duration,pow_description,ver_book,pow_page ";
      $q_string .= "from powers ";
      $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by pow_name,ver_version ";
      $q_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_powers) > 0) {
        while ($a_powers = mysqli_fetch_array($q_powers)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.power.fill.php?id="  . $a_powers['pow_id'] . "');jQuery('#dialogPower').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_power('add.power.del.php?id=" . $a_powers['pow_id'] . "');\">";
          $linkend = "</a>";

          $power_book = return_Book($a_powers['ver_book'], $a_powers['pow_page']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                . "</td>\n";
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

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.pow_name.value = '';\n";
      print "document.dialog.pow_type.value = '';\n";
      print "document.dialog.pow_range.value = '';\n";
      print "document.dialog.pow_action.value = '';\n";
      print "document.dialog.pow_duration.value = '';\n";
      print "document.dialog.pow_description.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
