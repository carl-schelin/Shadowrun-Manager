<?php
# Script: add.cyberdeck.mysql.php
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
    $package = "add.cyberdeck.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],             10);
        $formVars['deck_brand']    = clean($_GET['deck_brand'],     30);
        $formVars['deck_model']    = clean($_GET['deck_model'],     30);
        $formVars['deck_rating']   = clean($_GET['deck_rating'],    10);
        $formVars['deck_attack']   = clean($_GET['deck_attack'],    10);
        $formVars['deck_sleaze']   = clean($_GET['deck_sleaze'],    10);
        $formVars['deck_data']     = clean($_GET['deck_data'],      10);
        $formVars['deck_firewall'] = clean($_GET['deck_firewall'],  10);
        $formVars['deck_programs'] = clean($_GET['deck_programs'],  10);
        $formVars['deck_access']   = clean($_GET['deck_access'],    15);
        $formVars['deck_avail']    = clean($_GET['deck_avail'],     10);
        $formVars['deck_perm']     = clean($_GET['deck_perm'],      10);
        $formVars['deck_cost']     = clean($_GET['deck_cost'],      10);
        $formVars['deck_book']     = clean($_GET['deck_book'],      10);
        $formVars['deck_page']     = clean($_GET['deck_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['deck_rating'] == '') {
          $formVars['deck_rating'] = 0;
        }
        if ($formVars['deck_attack'] == '') {
          $formVars['deck_attack'] = 0;
        }
        if ($formVars['deck_sleaze'] == '') {
          $formVars['deck_sleaze'] = 0;
        }
        if ($formVars['deck_data'] == '') {
          $formVars['deck_data'] = 0;
        }
        if ($formVars['deck_firewall'] == '') {
          $formVars['deck_firewall'] = 0;
        }
        if ($formVars['deck_programs'] == '') {
          $formVars['deck_programs'] = 0;
        }
        if ($formVars['deck_avail'] == '') {
          $formVars['deck_avail'] = 0;
        }
        if ($formVars['deck_cost'] == '') {
          $formVars['deck_cost'] = 0;
        }
        if ($formVars['deck_page'] == '') {
          $formVars['deck_page'] = 0;
        }

        if (strlen($formVars['deck_brand']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "deck_brand      = \"" . $formVars['deck_brand']    . "\"," .
            "deck_model      = \"" . $formVars['deck_model']    . "\"," .
            "deck_rating     =   " . $formVars['deck_rating']   . "," .
            "deck_attack     =   " . $formVars['deck_attack']   . "," .
            "deck_sleaze     =   " . $formVars['deck_sleaze']   . "," .
            "deck_data       =   " . $formVars['deck_data']     . "," .
            "deck_firewall   =   " . $formVars['deck_firewall'] . "," .
            "deck_programs   =   " . $formVars['deck_programs'] . "," .
            "deck_access     = \"" . $formVars['deck_access']   . "\"," .
            "deck_avail      =   " . $formVars['deck_avail']    . "," .
            "deck_perm       = \"" . $formVars['deck_perm']     . "\"," .
            "deck_cost       =   " . $formVars['deck_cost']     . "," .
            "deck_book       = \"" . $formVars['deck_book']     . "\"," .
            "deck_page       =   " . $formVars['deck_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into cyberdeck set deck_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update cyberdeck set " . $q_string . " where deck_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['deck_brand']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Cyberdeck Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberdeck-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"cyberdeck-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Vehicles Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Vehicle from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Vehicle to toggle the form and edit the Vehicle.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Vehicles Management</strong> title bar to toggle the <strong>Vehicles Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Brand</th>\n";
      $output .=   "<th class=\"ui-state-default\">Model</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
      $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
      $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select deck_id,deck_brand,deck_model,deck_rating,deck_attack,deck_sleaze,deck_data,";
      $q_string .= "deck_firewall,deck_programs,deck_access,deck_avail,deck_perm,deck_cost,ver_book,deck_page ";
      $q_string .= "from cyberdeck ";
      $q_string .= "left join versions on versions.ver_id = cyberdeck.deck_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by deck_rating,deck_cost,ver_version ";
      $q_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_cyberdeck) > 0) {
        while ($a_cyberdeck = mysql_fetch_array($q_cyberdeck)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.cyberdeck.fill.php?id="  . $a_cyberdeck['deck_id'] . "');jQuery('#dialogCyberdeck').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberdeck('add.cyberdeck.del.php?id=" . $a_cyberdeck['deck_id'] . "');\">";
          $linkend = "</a>";

          $deck_avail = return_Avail($a_cyberdeck['deck_avail'], $a_cyberdeck['deck_perm'], 0, 0);

          $deck_rating = return_Rating($a_cyberdeck['deck_rating']);

          $deck_cost = return_Cost($a_cyberdeck['deck_cost']);

          $deck_book = return_Book($a_cyberdeck['ver_book'], $a_cyberdeck['deck_page']);

          $class = return_Class($a_cyberdeck['deck_perm']);

          $total = 0;
          $q_string  = "select r_deck_id ";
          $q_string .= "from r_cyberdeck ";
          $q_string .= "where r_deck_number = " . $a_cyberdeck['deck_id'] . " ";
          $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_cyberdeck) > 0) {
            while ($a_r_cyberdeck = mysql_fetch_array($q_r_cyberdeck)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"ui-widget-content delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"ui-widget-content delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_cyberdeck['deck_id']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberdeck['deck_brand'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberdeck['deck_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $deck_rating                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_attack']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_sleaze']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_data']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_firewall']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_programs']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_access']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $deck_avail                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $deck_cost                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $deck_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"14\">No Cyberdeck found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.deck_brand.value = '';\n";
      print "document.dialog.deck_model.value = '';\n";
      print "document.dialog.deck_rating.value = '';\n";
      print "document.dialog.deck_attack.value = '';\n";
      print "document.dialog.deck_sleaze.value = '';\n";
      print "document.dialog.deck_data.value = '';\n";
      print "document.dialog.deck_firewall.value = '';\n";
      print "document.dialog.deck_programs.value = '';\n";
      print "document.dialog.deck_access.value = '';\n";
      print "document.dialog.deck_avail.value = '';\n";
      print "document.dialog.deck_perm.value = '';\n";
      print "document.dialog.deck_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
