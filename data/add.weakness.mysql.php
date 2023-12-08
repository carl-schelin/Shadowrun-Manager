<?php
# Script: add.weakness.mysql.php
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
    $package = "add.weakness.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                 = clean($_GET['id'],                10);
        $formVars['weak_name']          = clean($_GET['weak_name'],         60);
        $formVars['weak_description']   = clean($_GET['weak_description'], 128);
        $formVars['weak_book']          = clean($_GET['weak_book'],         10);
        $formVars['weak_page']          = clean($_GET['weak_page'],         10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['weak_page'] == '') {
          $formVars['weak_page'] = 0;
        }

        if (strlen($formVars['weak_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "weak_name             = \"" . $formVars['weak_name']          . "\"," .
            "weak_description      = \"" . $formVars['weak_description']   . "\"," .
            "weak_book             =   " . $formVars['weak_book']          . "," .
            "weak_page             =   " . $formVars['weak_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into weakness set weak_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update weakness set " . $q_string . " where weak_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['weak_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Weakness Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('weakness-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"weakness-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select weak_id,weak_name,weak_description,ver_book,weak_page ";
      $q_string .= "from weakness ";
      $q_string .= "left join versions on versions.ver_id = weakness.weak_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by weak_name,ver_version ";
      $q_weakness = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_weakness) > 0) {
        while ($a_weakness = mysqli_fetch_array($q_weakness)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.weakness.fill.php?id="  . $a_weakness['weak_id'] . "');jQuery('#dialogWeakness').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_weakness('add.weakness.del.php?id=" . $a_weakness['weak_id'] . "');\">";
          $linkend = "</a>";

          $weakness_book = return_Book($a_weakness['ver_book'], $a_weakness['weak_page']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                            . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_weakness['weak_id']              . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_weakness['weak_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_weakness['weak_description']     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $weakness_book                      . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.weak_name.value = '';\n";
      print "document.dialog.weak_description.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
