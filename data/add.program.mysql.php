<?php
# Script: add.program.mysql.php
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
    $package = "add.program.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['pgm_name']       = clean($_GET['pgm_name'],       50);
        $formVars['pgm_type']       = clean($_GET['pgm_type'],       10);
        $formVars['pgm_desc']       = clean($_GET['pgm_desc'],      128);
        $formVars['pgm_avail']      = clean($_GET['pgm_avail'],      10);
        $formVars['pgm_perm']       = clean($_GET['pgm_perm'],       10);
        $formVars['pgm_cost']       = clean($_GET['pgm_cost'],       10);
        $formVars['pgm_book']       = clean($_GET['pgm_book'],       10);
        $formVars['pgm_page']       = clean($_GET['pgm_page'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['pgm_type'] == '') {
          $formVars['pgm_type'] = 0;
        }
        if ($formVars['pgm_avail'] == '') {
          $formVars['pgm_avail'] = 0;
        }
        if ($formVars['pgm_cost'] == '') {
          $formVars['pgm_cost'] = 0;
        }
        if ($formVars['pgm_page'] == '') {
          $formVars['pgm_page'] = 0;
        }

        if (strlen($formVars['pgm_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "pgm_name        = \"" . $formVars['pgm_name']       . "\"," .
            "pgm_type        =   " . $formVars['pgm_type']       . "," .
            "pgm_desc        = \"" . $formVars['pgm_desc']       . "\"," .
            "pgm_avail       =   " . $formVars['pgm_avail']      . "," .
            "pgm_perm        = \"" . $formVars['pgm_perm']       . "\"," .
            "pgm_cost        =   " . $formVars['pgm_cost']       . "," .
            "pgm_book        = \"" . $formVars['pgm_book']       . "\"," .
            "pgm_page        =   " . $formVars['pgm_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into program set pgm_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update program set " . $q_string . " where pgm_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['pgm_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $program_list = array("common", "hacking", "rigger", "righack");

      foreach ($program_list as &$program) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Program Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('program-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"program-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Program Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Program from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Program to toggle the form and edit the Program.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Program Management</strong> title bar to toggle the <strong>Program Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Description</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book</th>\n";
        $output .= "</tr>\n";

        if ($program == 'common') {
          $pgm_type = "0";
        }
        if ($program == 'hacking') {
          $pgm_type = "1";
        }
        if ($program == 'rigger') {
          $pgm_type = "2";
        }
        if ($program == 'righack') {
          $pgm_type = "3";
        }

        $q_string  = "select pgm_id,pgm_name,pgm_type,pgm_desc,pgm_avail,pgm_perm,pgm_cost,ver_book,pgm_page ";
        $q_string .= "from program ";
        $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
        $q_string .= "where pgm_type = " . $pgm_type . " and ver_admin = 1 ";
        $q_string .= "order by pgm_name,ver_version ";
        $q_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_program) > 0) {
          while ($a_program = mysqli_fetch_array($q_program)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.program.fill.php?id="  . $a_program['pgm_id'] . "');jQuery('#dialogProgram').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_program('add.program.del.php?id=" . $a_program['pgm_id'] . "');\">";
            $linkend = "</a>";

            $pgm_avail = return_Avail($a_program['pgm_avail'], $a_program['pgm_perm'], 0, 0);

            $pgm_cost = return_Cost($a_program['pgm_cost']);

            $pgm_book = return_Book($a_program['ver_book'], $a_program['pgm_page']);

            $class = return_Class($a_program['pgm_perm']);

            $total = 0;
            $q_string  = "select r_pgm_id ";
            $q_string .= "from r_program ";
            $q_string .= "where r_pgm_number = " . $a_program['pgm_id'] . " ";
            $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
            if (mysqli_num_rows($q_r_program) > 0) {
              while ($a_r_program = mysqli_fetch_array($q_r_program)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_program['pgm_id']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $total                            . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_program['pgm_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                                  . $a_program['pgm_desc']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                           . $pgm_avail                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                           . $pgm_cost                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                           . $pgm_book                         . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $program . "_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
      }

      print "document.dialog.pgm_name.value = '';\n";
      print "document.dialog.pgm_desc.value = '';\n";
      print "document.dialog.pgm_avail.value = '';\n";
      print "document.dialog.pgm_perm.value = '';\n";
      print "document.dialog.pgm_cost.value = '';\n";
      print "document.dialog.pgm_book.value = '';\n";
      print "document.dialog.pgm_page.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
