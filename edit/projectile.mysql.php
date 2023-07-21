<?php
# Script: projectile.mysql.php
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
    $package = "projectile.mysql.php";
    $formVars['update']                = clean($_GET['update'],                 10);
    $formVars['r_proj_character']      = clean($_GET['r_proj_character'],       10);
    $formVars['proj_class']            = clean($_GET['proj_class'],             10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_proj_character'] == '') {
      $formVars['r_proj_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_proj_number'] = clean($_GET['r_proj_number'], 10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_proj_number'] == '') {
          $formVars['r_proj_number'] = 1;
        }

        if ($formVars['r_proj_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_proj_character   =   " . $formVars['r_proj_character']   . "," .
            "r_proj_number      =   " . $formVars['r_proj_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_projectile set r_proj_id = NULL," . $q_string;
            $message = "Projectile Weapon added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_proj_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_proj_refresh\" value=\"Refresh My Projectile Listing\" onClick=\"javascript:attach_projectile('projectile.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_proj_update\"  value=\"Update Projectile\"          onClick=\"javascript:attach_projectile('projectile.mysql.php', 1);hideDiv('projectile-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_proj_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_proj_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Weapons Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Projectile Weapon: <span id=\"r_proj_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('projectile_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Projectile Weapons</th>\n";
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
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select proj_id,proj_class,class_name,proj_name,proj_acc,proj_damage,";
        $q_string .= "proj_rating,proj_type,proj_strength,proj_ap,proj_avail,";
        $q_string .= "proj_perm,proj_cost,ver_book,proj_page ";
        $q_string .= "from projectile ";
        $q_string .= "left join class on class.class_id = projectile.proj_class ";
        $q_string .= "left join versions on versions.ver_id = projectile.proj_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['proj_class'] > 0) {
          $q_string .= "and proj_class = " . $formVars['proj_class'] . " ";
        }
        $q_string .= "order by proj_name,proj_rating,proj_acc,proj_damage,proj_class,ver_version ";
        $q_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_projectile) > 0) {
          while ($a_projectile = mysql_fetch_array($q_projectile)) {

# this adds the proj_id to the r_proj_character
            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('projectile.mysql.php?update=-3&r_proj_character=" . $formVars['r_proj_character'] . "&proj_class=" . $a_projectile['proj_class'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('projectile.mysql.php?update=0&r_proj_character=" . $formVars['r_proj_character'] . "&r_proj_number=" . $a_projectile['proj_id'] . "');\">";
            $linkend = "</a>";

            $proj_rating = return_Rating($a_projectile['proj_rating']);

            $proj_damage = return_Strength($a_projectile['proj_damage'], $a_projectile['proj_type'], "", $a_projectile['proj_strength']);

            $proj_ap = return_Penetrate($a_projectile['proj_ap']);

            $proj_avail = return_Avail($a_projectile['proj_avail'], $a_projectile['proj_perm']);

            $proj_cost = return_Cost($a_projectile['proj_cost']);

            $proj_book = return_Book($a_projectile['ver_book'], $a_projectile['proj_page']);

            $class = return_Class($a_projectile['proj_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $filterstart . $a_projectile['class_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_projectile['proj_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_rating                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_projectile['proj_acc']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_damage                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_ap                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_avail                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_cost                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $proj_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('bows_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Projectile Weapons</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('projectile-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"projectile-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_proj_id,proj_id,class_name,proj_name,proj_rating,proj_acc,proj_damage,";
      $q_string .= "proj_type,proj_strength,proj_ap,proj_avail,";
      $q_string .= "proj_perm,proj_cost,ver_book,proj_page ";
      $q_string .= "from r_projectile ";
      $q_string .= "left join projectile on projectile.proj_id = r_projectile.r_proj_number ";
      $q_string .= "left join class on class.class_id = projectile.proj_class ";
      $q_string .= "left join versions on versions.ver_id = projectile.proj_book ";
      $q_string .= "where r_proj_character = " . $formVars['r_proj_character'] . " ";
      $q_string .= "order by class_name,proj_name ";
      $q_r_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_projectile) > 0) {
        while ($a_r_projectile = mysql_fetch_array($q_r_projectile)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_projacc(" . $a_r_projectile['r_proj_id'] . ");showDiv('projectile-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_projectile('projectile.del.php?id="  . $a_r_projectile['r_proj_id'] . "');\">";
          $linkend   = "</a>";

          $proj_rating = return_Rating($a_r_projectile['proj_rating']);

          $proj_damage = return_Strength($a_r_projectile['proj_damage'], $a_r_projectile['proj_type'], "", $a_r_projectile['proj_strength']);

          $proj_ap = return_Penetrate($a_r_projectile['proj_ap']);

          $costtotal += $a_r_projectile['proj_cost'];

          $proj_avail = return_Avail($a_r_projectile['proj_avail'], $a_r_projectile['proj_perm']);

          $proj_cost = return_Cost($a_r_projectile['proj_cost']);

          $proj_book = return_Book($a_r_projectile['ver_book'], $a_r_projectile['proj_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_proj_number']) && $formVars['r_proj_number'] == $a_r_projectile['proj_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_projectile['class_name']                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_projectile['proj_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_rating                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_projectile['proj_acc']                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_damage                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_ap                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_avail                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_cost                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_book                                           . "</td>\n";
          $output .= "</tr>\n";

# associate any ammo with the weapon
          $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_rating,ammo_ap,";
          $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
          $q_string .= "from r_ammo ";
          $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
          $q_string .= "left join class on class.class_id = ammo.ammo_class ";
          $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
          $q_string .= "where r_ammo_character = " . $formVars['r_proj_character'] . " and r_ammo_parentid = " . $a_r_projectile['r_proj_id'] . " ";
          $q_string .= "order by ammo_name,class_name ";
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_fireammo('fireammo.del.php?id="  . $a_r_ammo['r_ammo_id'] . "');\">";

              $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

              $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);

              $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

              $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                       . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating                                                . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                 . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                  . "</td>\n";
              $output .= "</tr>\n";

            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No Projectile Weapons added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_projectile);

      print "document.getElementById('my_projectile_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
