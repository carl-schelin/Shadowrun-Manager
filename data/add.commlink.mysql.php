<?php
# Script: add.commlink.mysql.php
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
    $package = "add.commlink.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],            10);
        $formVars['link_brand']     = clean($_GET['link_brand'],    30);
        $formVars['link_model']     = clean($_GET['link_model'],    30);
        $formVars['link_rating']    = clean($_GET['link_rating'],   10);
        $formVars['link_data']      = clean($_GET['link_data'],     10);
        $formVars['link_firewall']  = clean($_GET['link_firewall'], 10);
        $formVars['link_cost']      = clean($_GET['link_cost'],     10);
        $formVars['link_access']    = clean($_GET['link_access'],   15);
        $formVars['link_book']      = clean($_GET['link_book'],     10);
        $formVars['link_page']      = clean($_GET['link_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['link_cost'] == '') {
          $formVars['link_cost'] = 0;
        }
        if ($formVars['link_rating'] == '') {
          $formVars['link_rating'] = 0;
        }
        if ($formVars['link_data'] == '') {
          $formVars['link_data'] = 0;
        }
        if ($formVars['link_firewall'] == '') {
          $formVars['link_firewall'] = 0;
        }
        if ($formVars['link_page'] == '') {
          $formVars['link_page'] = 0;
        }

        if (strlen($formVars['link_model']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "link_brand    = \"" . $formVars['link_brand']    . "\"," .
            "link_model    = \"" . $formVars['link_model']    . "\"," .
            "link_cost     =   " . $formVars['link_cost']     . "," .
            "link_rating   =   " . $formVars['link_rating']   . "," .
            "link_data     =   " . $formVars['link_data']     . "," .
            "link_firewall =   " . $formVars['link_firewall'] . "," .
            "link_access   = \"" . $formVars['link_access']   . "\"," .
            "link_book     = \"" . $formVars['link_book']     . "\"," .
            "link_page     =   " . $formVars['link_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into commlink set link_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update commlink set " . $q_string . " where link_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['link_model']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Commlink Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlink-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"commlink-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Commlink Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Commlink from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Commlink to toggle the form and edit the Commlink.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Commlink Management</strong> title bar to toggle the <strong>Commlink Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $nuyen = '&yen;';
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"160\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select link_id,link_brand,link_model,link_rating,link_data,link_firewall,link_cost,link_access,link_avail,link_perm,ver_book,link_page ";
      $q_string .= "from commlink ";
      $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by link_rating,ver_version ";
      $q_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_commlink) > 0) {
        while ($a_commlink = mysql_fetch_array($q_commlink)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.commlink.fill.php?id="  . $a_commlink['link_id'] . "');jQuery('#dialogCommlink').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_commlink('add.commlink.del.php?id=" . $a_commlink['link_id'] . "');\">";
          $linkend = "</a>";

          $rating = return_Rating($a_commlink['link_rating']);

          $avail = return_Avail($a_commlink['link_avail'], $a_commlink['link_perm'], 0, 0);

          $cost = return_Cost($a_commlink['link_cost']);

          $book = return_Book($a_commlink['ver_book'], $a_commlink['link_page']);

          $class = return_Class($a_commlink['link_perm']);

          $total = 0;
          $q_string  = "select r_link_id ";
          $q_string .= "from r_commlink ";
          $q_string .= "where r_link_number = " . $a_commlink['link_id'] . " ";
          $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_commlink) > 0) {
            while ($a_r_commlink = mysql_fetch_array($q_r_commlink)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_commlink['link_id']                                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_commlink['link_brand'] . " " . $a_commlink['link_model']   . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $rating                                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_data']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_firewall']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_access']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $avail                                                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cost                                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $book                                                                    . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.link_brand.value = '';\n";
      print "document.dialog.link_model.value = '';\n";
      print "document.dialog.link_rating.value = '';\n";
      print "document.dialog.link_data.value = '';\n";
      print "document.dialog.link_firewall.value = '';\n";
      print "document.dialog.link_cost.value = '';\n";
      print "document.dialog.link_access.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
