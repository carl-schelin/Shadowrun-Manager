<?php
# Script: add.sprites.mysql.php
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
    $package = "add.sprites.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],                10);
        $formVars['sprite_name']       = clean($_GET['sprite_name'],       60);
        $formVars['sprite_attack']     = clean($_GET['sprite_attack'],     10);
        $formVars['sprite_sleaze']     = clean($_GET['sprite_sleaze'],     10);
        $formVars['sprite_data']       = clean($_GET['sprite_data'],       10);
        $formVars['sprite_firewall']   = clean($_GET['sprite_firewall'],   10);
        $formVars['sprite_initiative'] = clean($_GET['sprite_initiative'], 10);
        $formVars['sprite_book']       = clean($_GET['sprite_book'],       10);
        $formVars['sprite_page']       = clean($_GET['sprite_page'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['sprite_attack'] == '') {
          $formVars['sprite_attack'] = 0;
        }
        if ($formVars['sprite_sleaze'] == '') {
          $formVars['sprite_sleaze'] = 0;
        }
        if ($formVars['sprite_data'] == '') {
          $formVars['sprite_data'] = 0;
        }
        if ($formVars['sprite_firewall'] == '') {
          $formVars['sprite_firewall'] = 0;
        }
        if ($formVars['sprite_initiative'] == '') {
          $formVars['sprite_initiative'] = 0;
        }
        if ($formVars['sprite_page'] == '') {
          $formVars['sprite_page'] = 0;
        }

        if (strlen($formVars['sprite_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "sprite_name          = \"" . $formVars['sprite_name']       . "\"," .
            "sprite_attack        =   " . $formVars['sprite_attack']     . "," .
            "sprite_sleaze        =   " . $formVars['sprite_sleaze']     . "," .
            "sprite_data          =   " . $formVars['sprite_data']       . "," .
            "sprite_firewall      =   " . $formVars['sprite_firewall']   . "," .
            "sprite_initiative    =   " . $formVars['sprite_initiative'] . "," .
            "sprite_book          =   " . $formVars['sprite_book']       . "," .
            "sprite_page          =   " . $formVars['sprite_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into sprites set sprite_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update sprites set " . $q_string . " where sprite_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['sprite_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Sprite Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('sprite-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"sprite-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Metatype Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Metatype from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Metatype to toggle the form and edit the Metatype.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Metatype Management</strong> title bar to toggle the <strong>Metatype Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
      $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Initiative</th>\n";
      $output .=   "<th class=\"ui-state-default\">Initiative Dice</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select sprite_id,sprite_name,sprite_attack,sprite_sleaze,sprite_data,";
      $q_string .= "sprite_firewall,sprite_initiative,ver_book,sprite_page ";
      $q_string .= "from sprites ";
      $q_string .= "left join versions on versions.ver_id = sprites.sprite_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by sprite_name,ver_version ";
      $q_sprites = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_sprites) > 0) {
        while ($a_sprites = mysqli_fetch_array($q_sprites)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.sprites.fill.php?id="  . $a_sprites['sprite_id'] . "');jQuery('#dialogSprite').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_sprite('add.sprite.del.php?id=" . $a_sprites['sprite_id'] . "');\">";
          $linkend = "</a>";

          $sprite_attack   = return_Sprite(0, $a_sprites['sprite_attack']);

          $sprite_sleaze   = return_Sprite(0, $a_sprites['sprite_sleaze']);

          $sprite_data     = return_Sprite(0, $a_sprites['sprite_data']);

          $sprite_firewall = return_Sprite(0, $a_sprites['sprite_firewall']);

          $sprite_book = return_Book($a_sprites['ver_book'], $a_sprites['sprite_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_sprite_id ";
          $q_string .= "from r_sprite ";
          $q_string .= "where r_sprite_number = " . $a_sprites['sprite_id'] . " ";
          $q_r_sprite = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_sprite) > 0) {
            while ($a_r_sprite = mysqli_fetch_array($q_r_sprite)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_sprites['sprite_id']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_sprites['sprite_name']         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $sprite_attack                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $sprite_sleaze                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $sprite_data                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $sprite_firewall                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . "(Lx2) + " . $a_sprites['sprite_initiative'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . "4d6"                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $sprite_book                                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.sprite_name.value = '';\n";
      print "document.dialog.sprite_attack.value = '';\n";
      print "document.dialog.sprite_sleaze.value = '';\n";
      print "document.dialog.sprite_data.value = '';\n";
      print "document.dialog.sprite_firewall.value = '';\n";
      print "document.dialog.sprite_initiative.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
