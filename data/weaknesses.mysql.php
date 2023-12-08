<?php
# Script: weaknesses.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: This is used for spirits to add/modify the description

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "weaknesses.mysql.php";
    $formVars['update']              = clean($_GET['update'],       10);
    $formVars['sp_weak_creature']    = clean($_GET['r_spirit_id'],  10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['sp_weak_creature'] == '') {
      $formVars['sp_weak_creature'] = 0;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                       = clean($_GET['id'],                  10);
        $formVars['sp_weak_number']           = clean($_GET['weak_id'],             10);
        $formVars['sp_weak_specialize']       = clean($_GET['sp_weak_specialize'], 255);

        if ($formVars['sp_weak_number'] == '') {
          $formVars['sp_weak_number'] = 0;
        }

        if ($formVars['sp_weak_number'] > 0 || $formVars['id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          if ($formVars['update'] == 0) {
            $q_string = 
              "sp_weak_creature    =   " . $formVars['sp_weak_creature']  . "," .
              "sp_weak_number      =   " . $formVars['sp_weak_number'];

            $query = "insert into sp_weaknesses set sp_weak_id = NULL, " . $q_string;
          }

          if ($formVars['update'] == 1) {
            $q_string = 
              "sp_weak_specialize = \"" . $formVars['sp_weak_specialize']  . "\"";

            $query = "update sp_weaknesses set " . $q_string . " where sp_weak_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['sp_weak_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Weakness Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('Weakness-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"Weakness-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      if ($formVars['sp_weak_creature'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Select one of your Spirits in order to select a Weakness.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      } else {
        $q_string  = "select weak_id,weak_name,weak_description,ver_book,weak_page ";
        $q_string .= "from weakness ";
        $q_string .= "left join versions on versions.ver_id = weakness.weak_book ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by weak_name,ver_version ";
        $q_weakness = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_weakness) > 0) {
          while ($a_weakness = mysqli_fetch_array($q_weakness)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:attach_weaknesses('weaknesses.mysql.php?weak_id=" . $a_weakness['weak_id'] . "', 0);\">";
            $linkend = "</a>";

            $weakness_book = return_Book($a_weakness['ver_book'], $a_weakness['weak_page']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_weakness['weak_id']                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_weakness['weak_name']        . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                     . $a_weakness['weak_description']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $weakness_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
      }

      print "document.getElementById('weakness_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
