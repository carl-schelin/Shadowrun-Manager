<?php
# Script: add.metamagics.mysql.php
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
    $package = "add.metamagics.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],                    10);
        $formVars['meta_name']         = clean($_GET['meta_name'],             60);
        $formVars['meta_description']  = clean($_GET['meta_description'],     255);
        $formVars['meta_book']         = clean($_GET['meta_book'],             10);
        $formVars['meta_page']         = clean($_GET['meta_page'],             10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['meta_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "meta_name        = \"" . $formVars['meta_name']          . "\"," .
            "meta_description = \"" . $formVars['meta_description']   . "\"," .
            "meta_book        = \"" . $formVars['meta_book']          . "\"," .
            "meta_page        =   " . $formVars['meta_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into metamagics set meta_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update metamagics set " . $q_string . " where meta_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['meta_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Metamagics Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('metamagics-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"metamagics-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<p><strong>Metamagics Listing</p>\n";

      $output .= "<p>The main thing you'll want to know is WIL+REA is an Indirect Spell and INT+WIL is a Direct Spell.</p>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select meta_id,meta_name,meta_description,ver_book,meta_page ";
      $q_string .= "from metamagics ";
      $q_string .= "left join versions on versions.ver_id = metamagics.meta_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by meta_name,ver_version ";
      $q_metamagics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_metamagics) > 0) {
        while ($a_metamagics = mysqli_fetch_array($q_metamagics)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.metamagics.fill.php?id="  . $a_metamagics['meta_id'] . "');jQuery('#dialogMetamagics').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_metamagics('add.metamagics.del.php?id=" . $a_metamagics['meta_id'] . "');\">";
          $linkend = "</a>";

          $meta_book = return_Book($a_metamagics['ver_book'], $a_metamagics['meta_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_meta_id ";
          $q_string .= "from r_metamagics ";
          $q_string .= "where r_meta_number = " . $a_metamagics['meta_id'] . " ";
          $q_r_metamagics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_metamagics) > 0) {
            while ($a_r_metamagics = mysqli_fetch_array($q_r_metamagics)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                          . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_metamagics['meta_id']                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_metamagics['meta_name']     . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_metamagics['meta_description']                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $meta_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('metamagics_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    }

    print "document.dialog.meta_name.value = '';\n";
    print "document.dialog.mega_description.value = '';\n";

    print "$(\"#button-update\").button(\"disable\");\n";

  } else {
    logaccess($_SESSION['username'], $package, "Unauthorized access.");
  }

?>
