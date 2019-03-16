<?php
# Script: add.books.mysql.php
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
    $package = "add.books.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['ver_book']      = clean($_GET['ver_book'],      60);
        $formVars['ver_short']     = clean($_GET['ver_short'],     10);
        $formVars['ver_version']   = clean($_GET['ver_version'],   10);
        $formVars['ver_year']      = clean($_GET['ver_year'],      10);
        $formVars['ver_active']    = clean($_GET['ver_active'],    10);
        $formVars['ver_admin']     = clean($_GET['ver_admin'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['year'] == '') {
          $formVars['year'] = 0;
        }
        if ($formVars['ver_active'] == 'true') {
          $formVars['ver_active'] = 1;
        } else {
          $formVars['ver_active'] = 0;
        }
        if ($formVars['ver_admin'] == 'true') {
          $formVars['ver_admin'] = 1;
        } else {
          $formVars['ver_admin'] = 0;
        }

        if (strlen($formVars['ver_book']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "ver_book       = \"" . $formVars['ver_book']      . "\"," .
            "ver_short      = \"" . $formVars['ver_short']     . "\"," .
            "ver_version    = \"" . $formVars['ver_version']   . "\"," .
            "ver_year       =   " . $formVars['ver_year']      . "," . 
            "ver_active     =   " . $formVars['ver_active']    . "," . 
            "ver_admin      =   " . $formVars['ver_admin'];

          if ($formVars['update'] == 0) {
            $query = "insert into versions set ver_id = NULL, " . $q_string;
            $message = "Book added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update versions set " . $q_string . " where ver_id = " . $formVars['id'];
            $message = "Book updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['ver_book']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $book_list = array("1.0", "2.0", "3.0", "4.0", "4.5", "5.0", "6.0");

      foreach ($book_list as &$book) {

        $title = '';
        if ($book == '1.0') {
          $title = "sr1";
        }
        if ($book == '2.0') {
          $title = "sr2";
        }
        if ($book == '3.0') {
          $title = "sr3";
        }
        if ($book == '4.0') {
          $title = "sr4";
        }
        if ($book == '4.5') {
          $title = "sr4a";
        }
        if ($book == '5.0') {
          $title = "sr5";
        }
        if ($book == '6.0') {
          $title = "sr6";
        }

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Book Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $title . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $title . "-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Title</th>\n";
        $output .=   "<th class=\"ui-state-default\">Acronym</th>\n";
        $output .=   "<th class=\"ui-state-default\">Edition Year</th>\n";
        $output .=   "<th class=\"ui-state-default\">Active</th>\n";
        $output .=   "<th class=\"ui-state-default\">Admin</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select ver_id,ver_book,ver_short,ver_version,ver_year,ver_active,ver_admin ";
        $q_string .= "from versions ";
        $q_string .= "where ver_version = \"" . $book . "\" ";
        $q_string .= "order by ver_book ";
        $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_versions) > 0) {
          while ($a_versions = mysql_fetch_array($q_versions)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.books.fill.php?id="  . $a_versions['ver_id'] . "');jQuery('#dialogBook').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_book('add.books.del.php?id=" . $a_versions['ver_id'] . "');\">";
            $linkend = "</a>";

            if ($a_versions['ver_active']) {
              $active = 'Yes';
            } else {
              $active = 'No';
            }
            if ($a_versions['ver_admin']) {
              $admin = 'Yes';
            } else {
              $admin = 'No';
            }

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $linkstart . $a_versions['ver_id']    . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                     . $linkstart . $a_versions['ver_book']  . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                                  . $a_versions['ver_short']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                           . $a_versions['ver_year']             . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                           . $active                             . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                           . $admin                              . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $title . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }

      print "document.dialog.ver_book.value = '';\n";
      print "document.dialog.ver_short.value = '';\n";
      print "document.dialog.ver_version.value = '';\n";
      print "document.dialog.ver_year.value = '';\n";
      print "document.dialog.ver_active.value = false;\n";
      print "document.dialog.ver_admin.value = false;\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
