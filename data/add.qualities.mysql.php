<?php
# Script: add.qualities.mysql.php
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
    $package = "add.qualities.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']           = clean($_GET['id'],          10);
        $formVars['qual_name']    = clean($_GET['qual_name'],   50);
        $formVars['qual_value']   = clean($_GET['qual_value'],  10);
        $formVars['qual_desc']    = clean($_GET['qual_desc'],  160);
        $formVars['qual_book']    = clean($_GET['qual_book'],   10);
        $formVars['qual_page']    = clean($_GET['qual_page'],   10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['qual_value'] == '') {
          $formVars['qual_value'] = 0;
        }
        if ($formVars['qual_page'] == '') {
          $formVars['qual_page'] = 0;
        }

        if (strlen($formVars['qual_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "qual_name    = \"" . $formVars['qual_name']   . "\"," .
            "qual_value   =   " . $formVars['qual_value']  . "," .
            "qual_desc    = \"" . $formVars['qual_desc']   . "\"," .
            "qual_book    = \"" . $formVars['qual_book']   . "\"," .
            "qual_page    =   " . $formVars['qual_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into qualities set qual_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update qualities set " . $q_string . " where qual_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['qual_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $qualities_list = array("positive", "negative");

      foreach ($qualities_list as &$qualities) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        if ($qualities == 'positive') {
          $output .= "  <th class=\"ui-state-default\">Positive Qualities Listing</th>\n";
        } else {
          $output .= "  <th class=\"ui-state-default\">Negative Qualities Listing</th>\n";
        }
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('positive-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"positive-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Positive Qualities Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Quality from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Quality to toggle the form and edit the Quality.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"160\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Value</th>\n";
        $output .=   "<th class=\"ui-state-default\">Desc</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select qual_id,qual_name,qual_value,qual_desc,ver_book,qual_page ";
        $q_string .= "from qualities ";
        $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
        if ($qualities == "positive") {
          $q_string .= "where qual_value >= 0 ";
        } else {
          $q_string .= "where qual_value < 0 ";
        }
        $q_string .= "and ver_admin = 1 ";
        $q_string .= "order by qual_name,ver_version ";
        $q_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_qualities) > 0) {
          while ($a_qualities = mysql_fetch_array($q_qualities)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.qualities.fill.php?id="  . $a_qualities['qual_id'] . "');jQuery('#dialogQualities').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_qualities('add.qualities.del.php?id=" . $a_qualities['qual_id'] . "');\">";
            $linkend = "</a>";

            $total = 0;
            $q_string  = "select r_qual_id ";
            $q_string .= "from r_qualities ";
            $q_string .= "where r_qual_number = " . $a_qualities['qual_id'] . " ";
            $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_qualities) > 0) {
              while ($a_r_qualities = mysql_fetch_array($q_r_qualities)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"ui-widget-content delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"ui-widget-content delete\">" . $linkdel                                                    . "</td>\n";
            }
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_qualities['qual_id']                                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $total                                                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_qualities['qual_name']                        . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_qualities['qual_value']                                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                     . $a_qualities['qual_desc']                                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_qualities['ver_book'] . ": " . $a_qualities['qual_page'] . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $qualities . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }

      print "document.dialog.qual_name.value = '';\n";
      print "document.dialog.qual_value.value = '';\n";
      print "document.dialog.qual_desc.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
