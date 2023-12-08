<?php
# Script: add.mentor.mysql.php
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
    $package = "add.mentor.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                    = clean($_GET['id'],                    10);
        $formVars['mentor_name']           = clean($_GET['mentor_name'],           30);
        $formVars['mentor_all']            = clean($_GET['mentor_all'],           100);
        $formVars['mentor_mage']           = clean($_GET['mentor_mage'],          100);
        $formVars['mentor_adept']          = clean($_GET['mentor_adept'],         100);
        $formVars['mentor_disadvantage']   = clean($_GET['mentor_disadvantage'],  100);
        $formVars['mentor_book']           = clean($_GET['mentor_book'],           10);
        $formVars['mentor_page']           = clean($_GET['mentor_page'],           10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['mentor_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "mentor_name         = \"" . $formVars['mentor_name']          . "\"," .
            "mentor_all          = \"" . $formVars['mentor_all']           . "\"," .
            "mentor_mage         = \"" . $formVars['mentor_mage']          . "\"," .
            "mentor_adept        = \"" . $formVars['mentor_adept']         . "\"," .
            "mentor_disadvantage = \"" . $formVars['mentor_disadvantage']  . "\"," .
            "mentor_book         = \"" . $formVars['mentor_book']          . "\"," .
            "mentor_page         =   " . $formVars['mentor_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into mentor set mentor_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update mentor set " . $q_string . " where mentor_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['mentor_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Mentor Spirits</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('mentor-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"mentor-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Language Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Language from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Language to toggle the form and edit the Language.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Language Management</strong> title bar to toggle the <strong>Language Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mentor Spirit</th>\n";
      $output .=   "<th class=\"ui-state-default\">All</th>\n";
      $output .=   "<th class=\"ui-state-default\">Magicians</th>\n";
      $output .=   "<th class=\"ui-state-default\">Adepts</th>\n";
      $output .=   "<th class=\"ui-state-default\">Disadvantages</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,ver_book,mentor_page ";
      $q_string .= "from mentor ";
      $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by mentor_name,ver_version ";
      $q_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_mentor) > 0) {
        while ($a_mentor = mysqli_fetch_array($q_mentor)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.mentor.fill.php?id="  . $a_mentor['mentor_id'] . "');jQuery('#dialogMentor').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_mentor('add.mentor.del.php?id=" . $a_mentor['mentor_id'] . "');\">";
          $linkend = "</a>";

          $men_book = return_Book($a_mentor['ver_book'], $a_mentor['mentor_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_mentor_id ";
          $q_string .= "from r_mentor ";
          $q_string .= "where r_mentor_number = " . $a_mentor['mentor_id'] . " ";
          $q_r_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_mentor) > 0) {
            while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                    . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_mentor['mentor_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $total                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_mentor['mentor_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $a_mentor['mentor_all']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $a_mentor['mentor_mage']            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $a_mentor['mentor_adept']           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $a_mentor['mentor_disadvantage']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $men_book                           . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.mentor_name.value = '';\n";
      print "document.dialog.mentor_all.value = '';\n";
      print "document.dialog.mentor_mage.value = '';\n";
      print "document.dialog.mentor_adept.value = '';\n";
      print "document.dialog.mentor_disadvantage.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
