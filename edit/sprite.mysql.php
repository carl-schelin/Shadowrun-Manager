<?php
# Script: sprite.mysql.php
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
    $package = "sprite.mysql.php";
    $formVars['update']                 = clean($_GET['update'],                10);
    $formVars['r_sprite_character']     = clean($_GET['r_sprite_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_sprite_id']            = clean($_GET['id'],                    10);
        $formVars['r_sprite_number']        = clean($_GET['r_sprite_number'],       80);
        $formVars['r_sprite_level']         = clean($_GET['r_sprite_level'],        10);
        $formVars['r_sprite_tasks']         = clean($_GET['r_sprite_tasks'],        10);
        $formVars['r_sprite_registered']    = clean($_GET['r_sprite_registered'],   10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_sprite_level'] == '') {
          $formVars['r_sprite_level'] = 0;
        }
        if ($formVars['r_sprite_tasks'] == '') {
          $formVars['r_sprite_tasks'] = 0;
        }
        if ($formVars['r_sprite_registered'] == 'true') {
          $formVars['r_sprite_registered'] = 1;
        } else {
          $formVars['r_sprite_registered'] = 0;
        }

        if ($formVars['r_sprite_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_sprite_character   =   " . $formVars['r_sprite_character']   . "," .
            "r_sprite_number      =   " . $formVars['r_sprite_number']      . "," .
            "r_sprite_level       =   " . $formVars['r_sprite_level']       . "," . 
            "r_sprite_tasks       =   " . $formVars['r_sprite_tasks']       . "," . 
            "r_sprite_registered  =   " . $formVars['r_sprite_registered'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_sprite set r_sprite_id = NULL," . $q_string;
            $message = "Sprite compiled.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_sprite set " . $q_string . " where r_sprite_id = " . $formVars['r_sprite_id'];
            $message = "Sprite reprogrammed.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_sprite_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_sprite_refresh\" value=\"Refresh My Sprite Listing\" onClick=\"javascript:attach_sprite('sprite.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_sprite_update\"  value=\"Update Sprite\"          onClick=\"javascript:attach_sprite('sprite.mysql.php', 1);hideDiv('sprite-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_sprite_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_sprite_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Active Sprite Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Sprite: <span id=\"r_sprite_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Level: <input type=\"text\" name=\"r_sprite_level\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Tasks: <input type=\"text\" name=\"r_sprite_tasks\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Registered: <input type=\"checkbox\" name=\"r_sprite_registered\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('sprites_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Sprite Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('sprite-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"sprite-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
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
        $output .=   "<th class=\"ui-state-default\">Sprite Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
        $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Initiative</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select sprite_id,sprite_name,sprite_attack,sprite_sleaze,";
        $q_string .= "sprite_data,sprite_firewall,sprite_initiative,ver_book,sprite_page ";
        $q_string .= "from sprites ";
        $q_string .= "left join versions on versions.ver_id = sprites.sprite_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by sprite_name ";
        $q_sprites = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_sprites) > 0) {
          while ($a_sprites = mysqli_fetch_array($q_sprites)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('sprite.mysql.php?update=0&r_sprite_character=" . $formVars['r_sprite_character'] . "&r_sprite_number=" . $a_sprites['sprite_id'] . "');\">";
            $linkend = "</a>";

            $sprite_attack   = return_Sprite(0, $a_sprites['sprite_attack']);
            $sprite_sleaze   = return_Sprite(0, $a_sprites['sprite_sleaze']);
            $sprite_data     = return_Sprite(0, $a_sprites['sprite_data']);
            $sprite_firewall = return_Sprite(0, $a_sprites['sprite_firewall']);

            $sprite_book = return_Book($a_sprites['ver_book'], $a_sprites['sprite_page']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_sprites['sprite_name']         . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_attack                               . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_sleaze                               . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_data                                 . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_firewall                             . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "(Lx2) + " . $a_sprites['sprite_initiative'] . " + 4d6" . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_book                                 . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Sprites available.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_sprite);

        print "document.getElementById('sprites_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Sprite Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('sprite-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"sprite-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Sprite Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Tasks</th>\n";
      $output .=   "<th class=\"ui-state-default\">Registered</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
      $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Initiative</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_sprite_id,r_sprite_number,r_sprite_level,r_sprite_tasks,r_sprite_registered,";
      $q_string .= "sprite_name,sprite_attack,sprite_sleaze,sprite_data,sprite_firewall,sprite_initiative,";
      $q_string .= "ver_book,sprite_page ";
      $q_string .= "from r_sprite ";
      $q_string .= "left join sprites on sprites.sprite_id = r_sprite.r_sprite_number ";
      $q_string .= "left join versions on versions.ver_id = sprites.sprite_book ";
      $q_string .= "where r_sprite_character = " . $formVars['r_sprite_character'] . " ";
      $q_string .= "order by sprite_name ";
      $q_r_sprite = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_sprite) > 0) {
        while ($a_r_sprite = mysqli_fetch_array($q_r_sprite)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('sprite.fill.php?id=" . $a_r_sprite['r_sprite_id'] . "');showDiv('sprite-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_sprite('sprite.del.php?id="  . $a_r_sprite['r_sprite_id'] . "');\">";
          $linkend   = "</a>";

          if ($a_r_sprite['r_sprite_registered']) {
            $registered = 'Yes';
          } else {
            $registered = 'No';
          }

          $sprite_attack   = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_attack']);
          $sprite_sleaze   = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_sleaze']);
          $sprite_data     = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_data']);
          $sprite_firewall = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_firewall']);

          $sprite_initiative = "(Lx2) + " . $a_r_sprite['sprite_initiative'];
          if ($a_r_sprite['r_sprite_level'] > 0) {
            $sprite_initiative = (($a_r_sprite['r_sprite_level'] * 2) + $a_r_sprite['sprite_initiative']);
          }

          $sprite_book = return_Book($a_r_sprite['ver_book'], $a_r_sprite['sprite_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                              . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_sprite['sprite_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_level']         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_tasks']         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $registered                           . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_attack                        . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_sleaze                        . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_data                          . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_firewall                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_initiative . " + 4d6"         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $sprite_book                          . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Sprites added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_sprite);

      print "document.getElementById('my_sprites_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
