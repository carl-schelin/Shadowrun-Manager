<?php
# Script: commlink.mysql.php
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
    $package = "commlink.mysql.php";
    $formVars['update']             = clean($_GET['update'],              10);
    $formVars['r_link_character']   = clean($_GET['r_link_character'],    10);
    $formVars['r_link_id']          = clean($_GET['r_link_id'],           10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 1) {
        $formVars['r_link_number'] = clean($_GET['r_link_number'], 10);

        if ($formVars['r_link_number'] == '') {
          $formVars['r_link_number'] = 0;
        }

        if ($formVars['r_link_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

# get the company id
          $q_string  = "select link_access ";
          $q_string .= "from commlink ";
          $q_string .= "where link_id = " . $formVars['r_link_number'] . " ";
          $q_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_commlink = mysql_fetch_array($q_commlink);

          $link_access =
            $a_commlink['link_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_link_character   =   " . $formVars['r_link_character']   . "," .
            "r_link_number      =   " . $formVars['r_link_number']      . "," .
            "r_link_access      = \"" . $link_access                    . "\"";

          if ($formVars['update'] == 1) {
            $query = "insert into r_commlink set r_link_id = NULL," . $q_string;
            $message = "Commlink added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_link_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        }
      }

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_link_refresh\" value=\"Refresh My Commlink Listing\" onClick=\"javascript:attach_commlink('commlink.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_link_update\"  value=\"Update Commlink\"          onClick=\"javascript:attach_commlink('commlink.mysql.php', 1);hideDiv('commlink-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_link_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Commlink Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Commlink: <span id=\"r_link_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('commlink_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Commlinks</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlinks-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"commlinks-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Weapon Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

# by rating because they're all commlinks and that orders by the power.
        $q_string  = "select link_id,link_brand,link_model,link_rating,link_data,";
        $q_string .= "link_firewall,link_access,link_avail,link_perm,link_cost,ver_book,link_page ";
        $q_string .= "from commlink ";
        $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by link_rating,link_cost,ver_version ";
        $q_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_commlink) > 0) {
          while ($a_commlink = mysql_fetch_array($q_commlink)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:select_commlink('commlink.mysql.php?update=1&r_link_character=" . $formVars['r_link_character'] . "&r_link_number=" . $a_commlink['link_id'] . "');\">";
            $linkend = "</a>";

            $link_rating = return_Rating($a_commlink['link_rating']);

            $link_avail = return_Avail($a_commlink['link_avail'], $a_commlink['link_perm']);

            $link_cost = return_Cost($a_commlink['link_cost']);

            $link_book = return_Book($a_commlink['ver_book'], $a_commlink['link_page']);

            $class = return_Class($a_commlink['link_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_commlink['link_brand'] . " " . $a_commlink['link_model']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_rating                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_data']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_firewall']          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_access']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_avail                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_cost                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_book                            . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('commlink_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Commlink Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlink-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"commlink-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Company ID:Unit ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_link_id,link_id,link_brand,link_model,link_rating,link_data,link_firewall,";
      $q_string .= "r_link_access,r_link_active,link_avail,link_perm,link_cost,ver_book,link_page ";
      $q_string .= "from r_commlink ";
      $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
      $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
      $q_string .= "where r_link_character = " . $formVars['r_link_character'] . " ";
      $q_string .= "order by link_rating,link_cost,ver_version ";
      $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_commlink) > 0) {
        while ($a_r_commlink = mysql_fetch_array($q_r_commlink)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_linkacc(" . $a_r_commlink['r_link_id'] . ");showDiv('commlink-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_commlink('commlink.del.php?id="  . $a_r_commlink['r_link_id'] . "');\">";
          $linkend   = "</a>";

          $link_rating = return_Rating($a_r_commlink['link_rating']);

          $costtotal += $a_r_commlink['link_cost'];

          $link_avail = return_Avail($a_r_commlink['link_avail'], $a_r_commlink['link_perm']);

          $link_cost = return_Cost($a_r_commlink['link_cost']);

          $link_book = return_Book($a_r_commlink['ver_book'], $a_r_commlink['link_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_link_number']) && $formVars['r_link_number'] == $a_r_commlink['link_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $link_rating                                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_commlink['link_data']                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_commlink['link_firewall']                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_commlink['r_link_access']                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $link_avail                                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $link_cost                                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $link_book                                                                 . "</td>\n";
          $output .= "</tr>\n";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_link_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,acc_name,acc_rating,acc_essence,acc_capacity,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Commlinks\" and r_acc_character = " . $formVars['r_link_character'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_gearacc('gearacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                          . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                             . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                             . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                            . "</td>\n";
              $output .= "</tr>\n";
            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Commlinks added.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      mysql_free_result($q_r_commlink);

      print "document.getElementById('my_commlink_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
