<?php
# Script: mycommand.mysql.php
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
    $package = "mycommand.mysql.php";
    $formVars['update']              = clean($_GET['update'],              10);
    $formVars['r_cmd_character']     = clean($_GET['r_cmd_character'],     10);
    $formVars['r_cmd_id']            = clean($_GET['r_cmd_id'],            10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['cmd_id']          = clean($_GET['cmd_id'],          10);
        $formVars['r_cmd_noise']     = clean($_GET['r_cmd_noise'],     10);
        $formVars['r_cmd_sharing']   = clean($_GET['r_cmd_sharing'],   10);

        if ($formVars['cmd_id'] == '') {
          $formVars['cmd_id'] = 0;
        }
        if ($formVars['r_cmd_noise'] == '') {
          $formVars['r_cmd_noise'] = 0;
        }
        if ($formVars['r_cmd_sharing'] == '') {
          $formVars['r_cmd_sharing'] = 0;
        }

# cmd_id if we're adding, r_cmd_id if we're updating an existing deck.
        if ($formVars['cmd_id'] > 0 || $formVars['r_cmd_id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          if ($formVars['update'] == 0) {
            $query  = "select cmd_access ";
            $query .= "from command ";
            $query .= "where cmd_id = " . $formVars['cmd_id'] . " ";
            $q_command = mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
            $a_command = mysqli_fetch_array($q_command);

            $cmd_access =
              $a_command['cmd_access'] . ":" .
              dechex(rand(0,32767)) . ":" .
              dechex(rand(0,32767)) . ":" .
              dechex(rand(0,32767)) . ":" .
              dechex(rand(0,32767));

            $q_string =
              "r_cmd_character     =   " . $formVars['r_cmd_character']   . "," .
              "r_cmd_number        =   " . $formVars['cmd_id']            . "," .
              "r_cmd_noise         =   " . $formVars['r_cmd_noise']       . "," .
              "r_cmd_sharing       =   " . $formVars['r_cmd_sharing']     . "," .
              "r_cmd_access        = \"" . $cmd_access                    . "\"";

            $query = "insert into r_command set r_cmd_id = NULL," . $q_string;
            $message = "Rigger Command Console added.";
          }

          if ($formVars['update'] == 1) {
            $q_string =
              "r_cmd_noise         =   " . $formVars['r_cmd_noise']       . "," .
              "r_cmd_sharing       =   " . $formVars['r_cmd_sharing'];

            $query = "update r_command set " . $q_string . " where r_cmd_id = " . $formVars['r_cmd_id'];
            $message = "Rigger Command Console updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_cmd_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        }
      }

# adding a program to an existing command console
      if ($formVars['update'] == 3) {
        $formVars['pgm_id']            = clean($_GET['pgm_id'],           10);

        if ($formVars['pgm_id'] == '') {
          $formVars['pgm_id'] = 0;
        }

        if ($formVars['r_cmd_id'] > 0 && $formVars['pgm_id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_pgm_character   =   " . $formVars['r_cmd_character']   . "," .
            "r_pgm_command     =   " . $formVars['r_cmd_id']          . "," .
            "r_pgm_number      =   " . $formVars['pgm_id'];

          if ($formVars['update'] == 3) {
            $query = "insert into r_program set r_pgm_id = NULL," . $q_string;
            $message = "Program added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['pgm_id']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must select a command console before purchasing a program.\\n\\nClick on one of your command consoles to activate it.');\n";
        }
      }


      if ($formVars['update'] == -3) {

# print forms for each of the command console bits.
# first; the main one which sets the command console being edited. You can't buy anything else until you get a command console

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_cmd_refresh\" value=\"Refresh My Console Listing\" onClick=\"javascript:attach_command('mycommand.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_cmd_update\"  value=\"Update Console\"          onClick=\"javascript:attach_command('mycommand.mysql.php', 1);hideDiv('command-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_cmd_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_cmd_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Command Console Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Command Console: <span id=\"r_cmd_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Noise Reduction: <input type=\"text\" name=\"r_cmd_noise\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Sharing: <input type=\"text\" name=\"r_cmd_sharing\" size=\"10\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('command_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

# show your command console and all associated programs.
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Rigger Command Console Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('command-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"command-listing-help\" style=\"display: none\">\n";

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

      $costtotal = 0;
      $q_string  = "select r_cmd_id,cmd_brand,cmd_model,r_cmd_number,r_cmd_noise,r_cmd_sharing,cmd_data,";
      $q_string .= "cmd_firewall,cmd_programs,cmd_rating,cmd_avail,cmd_perm,r_cmd_access,cmd_cost,ver_book,cmd_page ";
      $q_string .= "from r_command ";
      $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
      $q_string .= "left join versions on versions.ver_id = command.cmd_book ";
      $q_string .= "where r_cmd_character = " . $formVars['r_cmd_character'] . " ";
      $q_string .= "order by cmd_brand,cmd_model,ver_version ";
      $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_command) > 0) {
        while ($a_r_command = mysqli_fetch_array($q_r_command)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_cmdacc(" . $a_r_command['r_cmd_id'] . ");showDiv('command-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_command('command.del.php?id="  . $a_r_command['r_cmd_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Command Console ID: " . $a_r_command['r_cmd_access'] . "</th>\n";
          $output .= "</tr>\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Del</th>\n";
          $output .=   "<th class=\"ui-state-default\">Console</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
          $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
          $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
          $output .=   "<th class=\"ui-state-default\">Noise Reduction</th>\n";
          $output .=   "<th class=\"ui-state-default\">Sharing</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $cmd_rating = return_Rating($a_r_command['cmd_rating']);

          $costtotal += $a_r_command['cmd_cost'];

          $cmd_avail = return_Avail($a_r_command['cmd_avail'], $a_r_command['cmd_perm']);

          $cmd_cost = return_Cost($a_r_command['cmd_cost']);

          $cmd_book = return_Book($a_r_command['ver_book'], $a_r_command['cmd_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $cmd_rating                                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['cmd_data']                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['cmd_firewall']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['cmd_programs']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_noise']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_sharing']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $cmd_avail                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $cmd_cost                                                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $cmd_book                                                       . "</td>\n";
          $output .= "</tr>\n";

          $output .= "</table>\n";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join class on class.class_id = accessory.acc_class ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Consoles\" and r_acc_character = " . $formVars['r_cmd_character'] . " and r_acc_parentid = " . $a_r_command['r_cmd_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {

            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Rigger Command Console Accessories</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Name</th>\n";
            $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cmdacc('cmdacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
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
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel      . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_accessory['acc_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                 . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                  . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

# show all common programs
          $q_string  = "select r_pgm_id,pgm_name,pgm_desc,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['r_cmd_character'] . " and r_pgm_command = " . $a_r_command['r_cmd_id'] . " and pgm_type = 2 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Rigger Command Console Common Programs</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" width=\"60\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Program</th>\n";
            $output .=   "<th class=\"ui-state-default\">Description</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_program = mysqli_fetch_array($q_r_program)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cmdpgm('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $costtotal += $a_r_program['pgm_cost'];

              $pgm_avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

              $pgm_cost = return_Cost($a_r_program['pgm_cost']);

              $pgm_book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                            . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_avail               . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_cost                . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_book                . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

# show all hacking programs
          $q_string  = "select r_pgm_id,pgm_name,pgm_desc,pgm_avail,pgm_perm,pgm_cost,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['r_cmd_character'] . " and r_pgm_command = " . $a_r_command['r_cmd_id'] . " and pgm_type = 3 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Command Console Hacking Programs</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" width=\"60\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Program</th>\n";
            $output .=   "<th class=\"ui-state-default\">Description</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_program = mysqli_fetch_array($q_r_program)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cmdpgm('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $costtotal += $a_r_program['pgm_cost'];

              $pgm_avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

              $pgm_cost = return_Cost($a_r_program['pgm_cost']);

              $pgm_book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                            . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_avail               . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_cost                . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_book                . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

        }
      } else {
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Command Console ID: " . $a_r_command['r_cmd_access'] . "</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Del</th>\n";
        $output .=   "<th class=\"ui-state-default\">Console</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Noise Reduction</th>\n";
        $output .=   "<th class=\"ui-state-default\">Sharing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No Command Consoles added.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      }

      mysql_free_result($q_r_command);

      print "document.getElementById('my_command_consoles_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
