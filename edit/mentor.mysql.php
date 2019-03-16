<?php
# Script: mentor.mysql.php
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
    $package = "mentor.mysql.php";
    $formVars['update']                 = clean($_GET['update'],                10);
    $formVars['r_mentor_character']     = clean($_GET['r_mentor_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_mentor_id']          = clean($_GET['id'],                 10);
        $formVars['r_mentor_number']      = clean($_GET['r_mentor_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_mentor_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_mentor_character   =   " . $formVars['r_mentor_character']   . "," .
            "r_mentor_number      =   " . $formVars['r_mentor_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_mentor set r_mentor_id = NULL," . $q_string;
            $message = "Mentor Spirit added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_mentor set " . $q_string . " where r_mentor_id = " . $formVars['r_mentor_id'];
            $message = "Mentor Spirit updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_mentor_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_mentor_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_mentor_number ";
          $q_string .= "from r_mentor ";
          $q_string .= "where r_mentor_character = " . $formVars['copyfrom'];
          $q_r_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_mentor = mysql_fetch_array($q_r_mentor)) {

            $q_string =
              "r_mentor_character     =   " . $formVars['r_mentor_character']      . "," .
              "r_mentor_number        =   " . $a_r_mentor['r_mentor_number'];
  
            $query = "insert into r_mentor set r_mentor_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Mentor Spirit Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('mentor-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"mentor-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Spell Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Mentor Spirit</th>\n";
        $output .=   "<th class=\"ui-state-default\">Advantages For All</th>\n";
        $output .=   "<th class=\"ui-state-default\">Mage Advantages</th>\n";
        $output .=   "<th class=\"ui-state-default\">Adept Advantages</th>\n";
        $output .=   "<th class=\"ui-state-default\">Disadvantages</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,ver_book,mentor_page ";
        $q_string .= "from mentor ";
        $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by mentor_name ";
        $q_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_mentor) > 0) {
          while ($a_mentor = mysql_fetch_array($q_mentor)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('mentor.mysql.php?update=0&r_mentor_character=" . $formVars['r_mentor_character'] . "&r_mentor_number=" . $a_mentor['mentor_id'] . "');\">";
            $linkend   = "</a>";

            $mentor_book = return_Book($a_mentor['ver_book'], $a_mentor['mentor_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">" . $linkstart . $a_mentor['mentor_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $a_mentor['mentor_all']             . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $a_mentor['mentor_mage']            . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $a_mentor['mentor_adept']           . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $a_mentor['mentor_disadvantage']    . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"       . $mentor_book                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Mentor Spirits available.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_mentor);

        print "document.getElementById('mentorspirits_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Mentor Spirit Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('mentor-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"mentor-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Spell Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mentor Spirit</th>\n";
      $output .=   "<th class=\"ui-state-default\">Advantages For All</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mage Advantages</th>\n";
      $output .=   "<th class=\"ui-state-default\">Adept Advantages</th>\n";
      $output .=   "<th class=\"ui-state-default\">Disadvantages</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_mentor_id,mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,ver_book,mentor_page ";
      $q_string .= "from r_mentor ";
      $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
      $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
      $q_string .= "where r_mentor_character = " . $formVars['r_mentor_character'] . " ";
      $q_string .= "order by mentor_name ";
      $q_r_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_mentor) > 0) {
        while ($a_r_mentor = mysql_fetch_array($q_r_mentor)) {

          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_mentor('mentor.del.php?id="  . $a_r_mentor['r_mentor_id'] . "');\">";

          $mentor_book = return_Book($a_r_mentor['ver_book'], $a_r_mentor['mentor_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_mentor_number']) && $formVars['r_mentor_number'] == $a_r_mentor['mentor_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_mentor['mentor_name']            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_mentor['mentor_all']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_mentor['mentor_mage']            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_mentor['mentor_adept']           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_mentor['mentor_disadvantage']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $mentor_book                          . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Mentor Spirits added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_mentor);

      print "document.getElementById('my_mentorspirits_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
