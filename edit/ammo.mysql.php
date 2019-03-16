<?php
# Script: ammo.mysql.php
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
    $package = "ammo.mysql.php";
    $formVars['update']             = clean($_GET['update'],              10);
    $formVars['r_ammo_id']          = clean($_GET['r_ammo_id'],           10);
    $formVars['r_ammo_character']   = clean($_GET['r_ammo_character'],    10);
    $formVars['ammo_class']         = clean($_GET['ammo_class'],          10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_ammo_character'] == '') {
      $formVars['r_ammo_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_ammo_number']      = clean($_GET['r_ammo_number'],       10);
        $formVars['r_ammo_rounds']      = clean($_GET['r_ammo_rounds'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_ammo_number'] == '') {
          $formVars['r_ammo_number'] = 0;
        }
        if ($formVars['r_ammo_rounds'] == '') {
          $formVars['r_ammo_rounds'] = 0;
        }

        if ($formVars['r_ammo_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_ammo_character   =   " . $formVars['r_ammo_character']   . "," .
            "r_ammo_number      =   " . $formVars['r_ammo_number']      . "," .
            "r_ammo_rounds      =   " . $formVars['r_ammo_rounds'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_ammo set r_ammo_id = null," . $q_string;
            $message = "Ammunition Added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update r_ammo set " . $q_string . " where r_ammo_id = " . $formVars['r_ammo_id'];
            $message = "Ammunition Updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_ammo_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_ammo_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_ammo_number ";
          $q_string .= "from r_ammo ";
          $q_string .= "where r_ammo_character = " . $formVars['copyfrom'];
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

            $q_string =
              "r_ammo_character     =   " . $formVars['r_ammo_character']   . "," .
              "r_ammo_number        =   " . $a_r_ammo['r_ammo_number'];
  
            $query = "insert into r_ammo set r_ammo_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_ammo_refresh\" value=\"Refresh My Ammo Listing\" onClick=\"javascript:attach_ammo('ammo.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_ammo_update\"  value=\"Update Ammo\"             onClick=\"javascript:attach_ammo('ammo.mysql.php', 1);hideDiv('ammo-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_ammo_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_ammo_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Ammunition Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Ammunition: <span id=\"r_ammo_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Ammunition: <input type=\"text\" name=\"r_ammo_rounds\" size=\"10\"> <span id=\"number_of_rounds\"></span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('ammo_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Ammunition</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ammo-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"ammo-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rounds</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage Modifier</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP Modifier</th>\n";
        $output .=   "<th class=\"ui-state-default\">Blast Radius</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select ammo_id,ammo_class,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_rating,ammo_ap,";
        $q_string .= "ammo_blast,ammo_avail,ammo_perm,ammo_cost,ver_book,ammo_page ";
        $q_string .= "from ammo ";
        $q_string .= "left join class on class.class_id = ammo.ammo_class ";
        $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['ammo_class'] > 0) {
          $q_string .= "and ammo_class = " . $formVars['ammo_class'] . " ";
        }
        $q_string .= "order by ammo_name,ammo_rating,class_name ";
        $q_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_ammo) > 0) {
          while ($a_ammo = mysql_fetch_array($q_ammo)) {

# this adds the ammo_id to the r_ammo_character
            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('ammo.mysql.php?update=-3&r_ammo_character=" . $formVars['r_ammo_character'] . "&ammo_class=" . $a_ammo['ammo_class'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('ammo.mysql.php?update=0&r_ammo_character=" . $formVars['r_ammo_character'] . "&r_ammo_number=" . $a_ammo['ammo_id'] . "');\">";
            $linkend = "</a>";

            $ammo_ap = return_Penetrate($a_ammo['ammo_ap']);

            $ammo_rating = return_Rating($a_ammo['ammo_rating']);

            $ammo_avail = return_Avail($a_ammo['ammo_avail'], $a_ammo['ammo_perm']);

            $ammo_cost = return_Cost($a_ammo['ammo_cost']);

            $ammo_book = return_Book($a_ammo['ver_book'], $a_ammo['ammo_page']);

            $class = return_Class($a_ammo['ammo_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $filterstart . $a_ammo['class_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_ammo['ammo_name']   . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_rounds']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_rating                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_mod']               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_ap                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_blast']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_avail                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_cost                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_book                        . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('ammo_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Ammunition</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ammo-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"ammo-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rounds</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Blast Radius</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costgrade = 0;
      $costtotal = 0;
      $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_ap,";
      $q_string .= "ammo_blast,ammo_avail,ammo_perm,ammo_cost,ver_book,ammo_page ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
      $q_string .= "where r_ammo_character = " . $formVars['r_ammo_character'] . " ";
      $q_string .= "order by ammo_name,ammo_rating,class_name ";
      $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('ammo.fill.php?id=" . $a_r_ammo['r_ammo_id'] . "');showDiv('ammo-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_ammo('ammo.del.php?id="  . $a_r_ammo['r_ammo_id'] . "');\">";
          $linkend   = "</a>";

          $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);

          $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

          $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

          $costgrade = ($a_r_ammo['ammo_cost'] * $a_r_ammo['r_ammo_rounds']);
          $costtotal += $costgrade;

          $ammo_cost = return_Cost($a_r_ammo['ammo_cost']);

          $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_ammo['class_name']                                     . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_ammo['ammo_name']              . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds'])     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_blast']                                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_cost                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                  . "</td>\n";
          $output .= "</tr>\n";

        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">Total Cost: " . return_Cost($costtotal) . ".</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Ammunition added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_ammo);

      print "document.getElementById('my_ammo_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
