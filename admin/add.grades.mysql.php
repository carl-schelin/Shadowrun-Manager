<?php
# Script: add.grades.mysql.php
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
    $package = "add.grades.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['grade_name']    = clean($_GET['grade_name'],    60);
        $formVars['grade_essence'] = clean($_GET['grade_essence'], 10);
        $formVars['grade_avail']   = clean($_GET['grade_avail'],   10);
        $formVars['grade_cost']    = clean($_GET['grade_cost'],    10);
        $formVars['grade_book']    = clean($_GET['grade_book'],    10);
        $formVars['grade_page']    = clean($_GET['grade_page'],    10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['grade_essence'] == '') {
          $formVars['grade_essence'] = 0;
        }
        if ($formVars['grade_avail'] == '') {
          $formVars['grade_avail'] = 0;
        }
        if ($formVars['grade_cost'] == '') {
          $formVars['grade_cost'] = 0;
        }

        if (strlen($formVars['grade_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "grade_name     = \"" . $formVars['grade_name']    . "\"," .
            "grade_essence  =   " . $formVars['grade_essence'] . "," .
            "grade_avail    =   " . $formVars['grade_avail']   . "," .
            "grade_cost     =   " . $formVars['grade_cost']    . "," . 
            "grade_book     =   " . $formVars['grade_book']    . "," . 
            "grade_page     =   " . $formVars['grade_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into grades set grade_id = NULL, " . $q_string;
            $message = "Bio/Cyberware Grade added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update grades set " . $q_string . " where grade_id = " . $formVars['id'];
            $message = "Bio/Cyberware Grade updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['grade_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Bio/Cyberware Grade Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('grade-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"grade-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Active Skill Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Active Skill from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an Active Skill to toggle the form and edit the Active Skill.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Active Skill Management</strong> title bar to toggle the <strong>Active Skill Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Grade</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence Multiplier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost Multiplier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select grade_id,grade_name,grade_essence,grade_avail,grade_cost,ver_book,grade_page ";
      $q_string .= "from grades ";
      $q_string .= "left join versions on versions.ver_id = grades.grade_book ";
      $q_string .= "order by grade_name ";
      $q_grades = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_grades) > 0) {
        while ($a_grades = mysql_fetch_array($q_grades)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.grades.fill.php?id="  . $a_grades['grade_id'] . "');jQuery('#dialogGrade').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_grade('add.grades.del.php?id=" . $a_grades['grade_id'] . "');\">";
          $linkend = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_grades['grade_id']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_grades['grade_name']                     . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_grades['grade_essence']                             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_grades['grade_avail']                               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_grades['grade_cost']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_grades['ver_book'] . ": " . $a_grades['grade_page'] . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('grades_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.grade_name.value = '';\n";
      print "document.dialog.grade_essence.value = '';\n";
      print "document.dialog.grade_avail.value = '';\n";
      print "document.dialog.grade_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
