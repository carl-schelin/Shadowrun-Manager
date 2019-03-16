<?php
# Script: add.projectile.mysql.php
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
    $package = "add.projectile.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],             10);
        $formVars['proj_class']    = clean($_GET['proj_class'],    10);
        $formVars['proj_name']     = clean($_GET['proj_name'],     60);
        $formVars['proj_rating']   = clean($_GET['proj_rating'],   10);
        $formVars['proj_acc']      = clean($_GET['proj_acc'],      10);
        $formVars['proj_damage']   = clean($_GET['proj_damage'],   10);
        $formVars['proj_type']     = clean($_GET['proj_type'],     10);
        $formVars['proj_strength'] = clean($_GET['proj_strength'], 10);
        $formVars['proj_ap']       = clean($_GET['proj_ap'],       10);
        $formVars['proj_avail']    = clean($_GET['proj_avail'],    10);
        $formVars['proj_perm']     = clean($_GET['proj_perm'],     10);
        $formVars['proj_cost']     = clean($_GET['proj_cost'],     10);
        $formVars['proj_book']     = clean($_GET['proj_book'],     10);
        $formVars['proj_page']     = clean($_GET['proj_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['proj_rating'] == '') {
          $formVars['proj_rating'] = 0;
        }
        if ($formVars['proj_acc'] == '') {
          $formVars['proj_acc'] = 0;
        }
        if ($formVars['proj_damage'] == '') {
          $formVars['proj_damage'] = 0;
        }
        if ($formVars['proj_strength'] == 'true') {
          $formVars['proj_strength'] = 1;
        } else {
          $formVars['proj_strength'] = 0;
        }
        if ($formVars['proj_ap'] == '') {
          $formVars['proj_ap'] = 0;
        }
        if ($formVars['proj_avail'] == '') {
          $formVars['proj_avail'] = 0;
        }
        if ($formVars['proj_cost'] == '') {
          $formVars['proj_cost'] = 0;
        }
        if ($formVars['proj_page'] == '') {
          $formVars['proj_page'] = 0;
        }

        if (strlen($formVars['proj_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "proj_class       =   " . $formVars['proj_class']    . "," .
            "proj_name        = \"" . $formVars['proj_name']     . "\"," .
            "proj_rating      =   " . $formVars['proj_rating']   . "," .
            "proj_acc         =   " . $formVars['proj_acc']      . "," .
            "proj_damage      =   " . $formVars['proj_damage']   . "," .
            "proj_type        = \"" . $formVars['proj_type']     . "\"," .
            "proj_strength    =   " . $formVars['proj_strength'] . "," .
            "proj_ap          =   " . $formVars['proj_ap']       . "," .
            "proj_avail       =   " . $formVars['proj_avail']    . "," .
            "proj_perm        = \"" . $formVars['proj_perm']     . "\"," .
            "proj_cost        =   " . $formVars['proj_cost']     . "," .
            "proj_book        = \"" . $formVars['proj_book']     . "\"," .
            "proj_page        =   " . $formVars['proj_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into projectile set proj_id = NULL, " . $q_string;
            $message = "Projectile Weapon added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update projectile set " . $q_string . " where proj_id = " . $formVars['id'];
            $message = "Projectile Weapon updated.";
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
      $output .= "  <th class=\"ui-state-default\">Projectile Weapon Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('projectile-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"projectile-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Location</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select proj_id,class_name,proj_name,proj_rating,proj_acc,proj_damage,proj_type,proj_strength, ";
      $q_string .= "proj_ap,proj_avail,proj_perm,proj_cost,ver_book,proj_page ";
      $q_string .= "from projectile ";
      $q_string .= "left join class on class.class_id = projectile.proj_class ";
      $q_string .= "left join versions on versions.ver_id = projectile.proj_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by class_name,proj_name,proj_rating,ver_version ";
      $q_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_projectile) > 0) {
        while ($a_projectile = mysql_fetch_array($q_projectile)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.projectile.fill.php?id="  . $a_projectile['proj_id'] . "');jQuery('#dialogProjectile').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_projectile('add.projectile.del.php?id=" . $a_projectile['proj_id'] . "');\">";
          $linkend = "</a>";

          $proj_rating = '--';
          if ($a_projectile['proj_rating'] > 0) {
            $proj_rating = $a_projectile['proj_rating'];
          }

          $proj_damage = return_Strength($a_projectile['proj_damage'], $a_projectile['proj_type'], "", $a_projectile['proj_strength']);

          $proj_ap = return_Penetrate($a_projectile['proj_ap']);

          $proj_avail = return_Avail($a_projectile['proj_avail'], $a_projectile['proj_perm']);

          $class = return_Class($a_projectile['proj_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_projectile['proj_id']                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_projectile['class_name']                          . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_projectile['proj_name']                           . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $proj_rating                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_projectile['proj_acc']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $proj_damage                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $proj_ap                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $proj_avail                                                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . number_format($a_projectile['proj_cost'], 0, '.', ',') . $nuyen . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_projectile['ver_book'] . ": " . $a_projectile['proj_page']   . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.proj_name.value = '';\n";
      print "document.dialog.proj_rating.value = '';\n";
      print "document.dialog.proj_acc.value = '';\n";
      print "document.dialog.proj_damage.value = '';\n";
      print "document.dialog.proj_type.value = '';\n";
      print "document.dialog.proj_strength.checked = false;\n";
      print "document.dialog.proj_ap.value = '';\n";
      print "document.dialog.proj_avail.value = '';\n";
      print "document.dialog.proj_perm.value = '';\n";
      print "document.dialog.proj_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
