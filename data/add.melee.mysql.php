<?php
# Script: add.melee.mysql.php
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
    $package = "add.melee.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['melee_class']    = clean($_GET['melee_class'],    30);
        $formVars['melee_name']     = clean($_GET['melee_name'],     50);
        $formVars['melee_acc']      = clean($_GET['melee_acc'],      10);
        $formVars['melee_reach']    = clean($_GET['melee_reach'],    50);
        $formVars['melee_damage']   = clean($_GET['melee_damage'],   10);
        $formVars['melee_type']     = clean($_GET['melee_type'],     10);
        $formVars['melee_flag']     = clean($_GET['melee_flag'],     10);
        $formVars['melee_strength'] = clean($_GET['melee_strength'], 10);
        $formVars['melee_ap']       = clean($_GET['melee_ap'],       10);
        $formVars['melee_avail']    = clean($_GET['melee_avail'],    10);
        $formVars['melee_perm']     = clean($_GET['melee_perm'],     10);
        $formVars['melee_cost']     = clean($_GET['melee_cost'],     10);
        $formVars['melee_book']     = clean($_GET['melee_book'],     10);
        $formVars['melee_page']     = clean($_GET['melee_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['melee_acc'] == '') {
          $formVars['melee_acc'] = 0;
        }
        if ($formVars['melee_damage'] == '') {
          $formVars['melee_damage'] = 0;
        }
        if ($formVars['melee_reach'] == '') {
          $formVars['melee_reach'] = 0;
        }
        if ($formVars['melee_strength'] == 'true') {
          $formVars['melee_strength'] = 1;
        } else {
          $formVars['melee_strength'] = 0;
        }
        if ($formVars['melee_ap'] == '') {
          $formVars['melee_ap'] = 0;
        }
        if ($formVars['melee_avail'] == '') {
          $formVars['melee_avail'] = 0;
        }
        if ($formVars['melee_cost'] == '') {
          $formVars['melee_cost'] = 0;
        }
        if ($formVars['melee_page'] == '') {
          $formVars['melee_page'] = 0;
        }

        if (strlen($formVars['melee_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "melee_class       = \"" . $formVars['melee_class']    . "\"," .
            "melee_name        = \"" . $formVars['melee_name']     . "\"," .
            "melee_acc         =   " . $formVars['melee_acc']      . "," .
            "melee_reach       =   " . $formVars['melee_reach']    . "," .
            "melee_damage      =   " . $formVars['melee_damage']   . "," .
            "melee_type        = \"" . $formVars['melee_type']     . "\"," .
            "melee_flag        = \"" . $formVars['melee_flag']     . "\"," .
            "melee_strength    =   " . $formVars['melee_strength'] . "," .
            "melee_ap          =   " . $formVars['melee_ap']       . "," .
            "melee_avail       =   " . $formVars['melee_avail']    . "," .
            "melee_perm        = \"" . $formVars['melee_perm']     . "\"," .
            "melee_cost        =   " . $formVars['melee_cost']     . "," .
            "melee_book        = \"" . $formVars['melee_book']     . "\"," .
            "melee_page        =   " . $formVars['melee_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into melee set melee_id = NULL, " . $q_string;
            $message = "Melee Weapon added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update melee set " . $q_string . " where melee_id = " . $formVars['id'];
            $message = "Melee Weapon updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['melee_name']);

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
      $output .= "  <th class=\"ui-state-default\">Melee Weapon Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('melee-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"melee-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
      $output .=   "<th class=\"ui-state-default\">Reach</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Location</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select melee_id,class_name,melee_class,melee_name,melee_acc,melee_reach,";
      $q_string .= "melee_damage,melee_type,melee_flag,melee_strength,melee_ap,melee_avail,";
      $q_string .= "melee_perm,melee_cost,ver_book,melee_page ";
      $q_string .= "from melee ";
      $q_string .= "left join class on class.class_id = melee.melee_class ";
      $q_string .= "left join versions on versions.ver_id = melee.melee_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by melee_class,melee_name,ver_version ";
      $q_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_melee) > 0) {
        while ($a_melee = mysql_fetch_array($q_melee)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.melee.fill.php?id="  . $a_melee['melee_id'] . "');jQuery('#dialogMelee').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_melee('add.melee.del.php?id=" . $a_melee['melee_id'] . "');\">";
          $linkend = "</a>";

          $melee_reach = return_Reach($a_melee['melee_reach']);

          $melee_damage = return_Strength($a_melee['melee_damage'], $a_melee['melee_type'], $a_melee['melee_flag'], $a_melee['melee_strength']);

          $melee_ap = return_Penetrate($a_melee['melee_ap']);

          $melee_avail = return_Avail($a_melee['melee_avail'], $a_melee['melee_perm']);

          $melee_cost = return_Cost($a_melee['melee_cost']);

          $melee_book = return_Book($a_melee['ver_book'], $a_melee['melee_page']);

          $class = return_Class($a_melee['melee_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_melee['melee_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_melee['class_name']            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_melee['melee_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_melee['melee_acc']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_reach                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_damage                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_ap                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_avail                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_cost                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $melee_book                       . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"14\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.melee_name.value = '';\n";
      print "document.dialog.melee_acc.value = '';\n";
      print "document.dialog.melee_reach.value = '';\n";
      print "document.dialog.melee_damage.value = '';\n";
      print "document.dialog.melee_type.value = '';\n";
      print "document.dialog.melee_flag.value = '';\n";
      print "document.dialog.melee_strength.checked = false;\n";
      print "document.dialog.melee_ap.value = '';\n";
      print "document.dialog.melee_avail.value = '';\n";
      print "document.dialog.melee_perm.value = '';\n";
      print "document.dialog.melee_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
