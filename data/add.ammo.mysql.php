<?php
# Script: add.ammo.mysql.php
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
    $package = "add.ammo.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']          = clean($_GET['id'],          10);
        $formVars['ammo_class']  = clean($_GET['ammo_class'],  10);
        $formVars['ammo_name']   = clean($_GET['ammo_name'],   50);
        $formVars['ammo_mod']    = clean($_GET['ammo_mod'],    20);
        $formVars['ammo_rounds'] = clean($_GET['ammo_rounds'], 10);
        $formVars['ammo_rating'] = clean($_GET['ammo_rating'], 10);
        $formVars['ammo_ap']     = clean($_GET['ammo_ap'],     10);
        $formVars['ammo_blast']  = clean($_GET['ammo_blast'],  15);
        $formVars['ammo_armor']  = clean($_GET['ammo_armor'],   5);
        $formVars['ammo_avail']  = clean($_GET['ammo_avail'],  10);
        $formVars['ammo_perm']   = clean($_GET['ammo_perm'],    5);
        $formVars['ammo_cost']   = clean($_GET['ammo_cost'],   10);
        $formVars['ammo_book']   = clean($_GET['ammo_book'],   10);
        $formVars['ammo_page']   = clean($_GET['ammo_page'],   10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['ammo_rating'] == '') {
          $formVars['ammo_rating'] = 0;
        }
        if ($formVars['ammo_ap'] == '') {
          $formVars['ammo_ap'] = 0;
        }
        if ($formVars['ammo_avail'] == '') {
          $formVars['ammo_avail'] = 0;
        }
        if ($formVars['ammo_cost'] == '') {
          $formVars['ammo_cost'] = 0;
        }
        if ($formVars['ammo_page'] == '') {
          $formVars['ammo_page'] = 0;
        }

        if (strlen($formVars['ammo_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "ammo_class       =   " . $formVars['ammo_class']   . "," .
            "ammo_name        = \"" . $formVars['ammo_name']    . "\"," .
            "ammo_mod         = \"" . $formVars['ammo_mod']     . "\"," .
            "ammo_rounds      =   " . $formVars['ammo_rounds']  . "," .
            "ammo_rating      =   " . $formVars['ammo_rating']  . "," .
            "ammo_ap          =   " . $formVars['ammo_ap']      . "," .
            "ammo_blast       = \"" . $formVars['ammo_blast']   . "\"," .
            "ammo_armor       = \"" . $formVars['ammo_armor']   . "\"," .
            "ammo_avail       =   " . $formVars['ammo_avail']   . "," .
            "ammo_perm        = \"" . $formVars['ammo_perm']    . "\"," .
            "ammo_cost        =   " . $formVars['ammo_cost']    . "," .
            "ammo_book        = \"" . $formVars['ammo_book']    . "\"," .
            "ammo_page        =   " . $formVars['ammo_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into ammo set ammo_id = NULL, " . $q_string;
            $message = "Ammunition added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update ammo set " . $q_string . " where ammo_id = " . $formVars['id'];
            $message = "Ammunition updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['ammo_name']);

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
      $output .= "  <th class=\"ui-state-default\">Ammunition Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ammo-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"ammo-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Ammunition Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rounds</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Blast</th>\n";
      $output .=   "<th class=\"ui-state-default\">Armor</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,ammo_blast,";
      $q_string .= "ammo_armor,ammo_rating,ammo_avail,ammo_perm,ammo_cost,ver_book,ammo_page ";
      $q_string .= "from ammo ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by class_name,ammo_name,ammo_rating,ver_version ";
      $q_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_ammo) > 0) {
        while ($a_ammo = mysql_fetch_array($q_ammo)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.ammo.fill.php?id="  . $a_ammo['ammo_id'] . "');jQuery('#dialogAmmunition').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_ammo('add.ammo.del.php?id=" . $a_ammo['ammo_id'] . "');\">";
          $linkend = "</a>";

          $ammo_ap = return_Penetrate($a_ammo['ammo_ap']);

          $ammo_rating = return_Rating($a_ammo['ammo_rating']);

          $ammo_avail = return_Avail($a_ammo['ammo_avail'], $a_ammo['ammo_perm']);

          $class = return_Class($a_ammo['ammo_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_ammo['ammo_id']                                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_ammo['class_name']                                     . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_ammo['ammo_name']                                      . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_rounds']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_rating                                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_mod']                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_ap                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_blast']                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ammo_armor']                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $ammo_avail                                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . number_format($a_ammo['ammo_cost'], 0, '.', ',') . $nuyen            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ammo['ver_book']    . ": " . $a_ammo['ammo_page']                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.ammo_class.value = '';\n";
      print "document.dialog.ammo_name.value = '';\n";
      print "document.dialog.ammo_mod.value = '';\n";
      print "document.dialog.ammo_rounds.value = '';\n";
      print "document.dialog.ammo_rating.value = '';\n";
      print "document.dialog.ammo_ap.value = '';\n";
      print "document.dialog.ammo_blast.value = '';\n";
      print "document.dialog.ammo_armor.value = '';\n";
      print "document.dialog.ammo_avail.value = '';\n";
      print "document.dialog.ammo_perm.value = '';\n";
      print "document.dialog.ammo_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
