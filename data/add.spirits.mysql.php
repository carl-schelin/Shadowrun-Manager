<?php
# Script: add.spirits.mysql.php
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
    $package = "add.spirits.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                     = clean($_GET['id'],                       10);
        $formVars['spirit_name']            = clean($_GET['spirit_name'],              60);
        $formVars['spirit_body']            = clean($_GET['spirit_body'],              10);
        $formVars['spirit_agility']         = clean($_GET['spirit_agility'],           10);
        $formVars['spirit_reaction']        = clean($_GET['spirit_reaction'],          10);
        $formVars['spirit_strength']        = clean($_GET['spirit_strength'],          10);
        $formVars['spirit_willpower']       = clean($_GET['spirit_willpower'],         10);
        $formVars['spirit_logic']           = clean($_GET['spirit_logic'],             10);
        $formVars['spirit_intuition']       = clean($_GET['spirit_intuition'],         10);
        $formVars['spirit_charisma']        = clean($_GET['spirit_charisma'],          10);
        $formVars['spirit_edge']            = clean($_GET['spirit_edge'],              10);
        $formVars['spirit_essence']         = clean($_GET['spirit_essence'],           10);
        $formVars['spirit_magic']           = clean($_GET['spirit_magic'],             10);
        $formVars['spirit_description']     = clean($_GET['spirit_description'],      255);
        $formVars['spirit_book']            = clean($_GET['spirit_book'],              10);
        $formVars['spirit_page']            = clean($_GET['spirit_page'],              10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['spirit_body'] == '') {
          $formVars['spirit_body'] = 0;
        }
        if ($formVars['spirit_agility'] == '') {
          $formVars['spirit_agility'] = 0;
        }
        if ($formVars['spirit_reaction'] == '') {
          $formVars['spirit_reaction'] = 0;
        }
        if ($formVars['spirit_strength'] == '') {
          $formVars['spirit_strength'] = 0;
        }
        if ($formVars['spirit_willpower'] == '') {
          $formVars['spirit_willpower'] = 0;
        }
        if ($formVars['spirit_logic'] == '') {
          $formVars['spirit_logic'] = 0;
        }
        if ($formVars['spirit_intuition'] == '') {
          $formVars['spirit_intuition'] = 0;
        }
        if ($formVars['spirit_charisma'] == '') {
          $formVars['spirit_charisma'] = 0;
        }
        if ($formVars['spirit_edge'] == '') {
          $formVars['spirit_edge'] = 0;
        }
        if ($formVars['spirit_essence'] == '') {
          $formVars['spirit_essence'] = 0;
        }
        if ($formVars['spirit_magic'] == '') {
          $formVars['spirit_magic'] = 0;
        }
        if ($formVars['spirit_page'] == '') {
          $formVars['spirit_page'] = 0;
        }

        if (strlen($formVars['spirit_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "spirit_name         = \"" . $formVars['spirit_name']        . "\"," .
            "spirit_body         =   " . $formVars['spirit_body']        . "," .
            "spirit_agility      =   " . $formVars['spirit_agility']     . "," .
            "spirit_reaction     =   " . $formVars['spirit_reaction']    . "," .
            "spirit_strength     =   " . $formVars['spirit_strength']    . "," .
            "spirit_willpower    =   " . $formVars['spirit_willpower']   . "," .
            "spirit_logic        =   " . $formVars['spirit_logic']       . "," .
            "spirit_intuition    =   " . $formVars['spirit_intuition']   . "," .
            "spirit_charisma     =   " . $formVars['spirit_charisma']    . "," .
            "spirit_edge         =   " . $formVars['spirit_edge']        . "," .
            "spirit_essence      =   " . $formVars['spirit_essence']     . "," .
            "spirit_magic        =   " . $formVars['spirit_magic']       . "," .
            "spirit_description  = \"" . $formVars['spirit_description'] . "\"," .
            "spirit_book         =   " . $formVars['spirit_book']        . "," .
            "spirit_page         =   " . $formVars['spirit_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into spirits set spirit_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update spirits set " . $q_string . " where spirit_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['spirit_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_spirit_refresh\" value=\"Refresh Spirit Listing\" onClick=\"javascript:attach_power('add.spirit.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_spirit_update\"  value=\"Update Spirit\"          onClick=\"javascript:attach_power('add.spirit.mysql.php', 1);hideDiv('spirit-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_spirit_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Spirit Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Spirit: <span id=\"r_spirit_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('spirit_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Spirit Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spirit-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"spirit-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Select</th>\n";
      $output .=   "<th class=\"ui-state-default\">Edit</th>\n";
      $output .=   "<th class=\"ui-state-default\">Body</th>\n";
      $output .=   "<th class=\"ui-state-default\">Agility</th>\n";
      $output .=   "<th class=\"ui-state-default\">Reaction</th>\n";
      $output .=   "<th class=\"ui-state-default\">Strength</th>\n";
      $output .=   "<th class=\"ui-state-default\">Willpower</th>\n";
      $output .=   "<th class=\"ui-state-default\">Logic</th>\n";
      $output .=   "<th class=\"ui-state-default\">Intuition</th>\n";
      $output .=   "<th class=\"ui-state-default\">Charisma</th>\n";
      $output .=   "<th class=\"ui-state-default\">Edge</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Magic</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select spirit_id,spirit_name,spirit_body,spirit_agility,spirit_reaction,";
      $q_string .= "spirit_strength,spirit_willpower,spirit_logic,spirit_intuition,";
      $q_string .= "spirit_charisma,spirit_edge,spirit_essence,spirit_magic,spirit_description,";
      $q_string .= "ver_book,spirit_page ";
      $q_string .= "from spirits ";
      $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by spirit_name,ver_version ";
      $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_spirits) > 0) {
        while ($a_spirits = mysqli_fetch_array($q_spirits)) {

          $linkstart     = "<a href=\"#\" onclick=\"";
          $linkstart    .= "javascript:show_file('add.spirits.fill.php?id="  . $a_spirits['spirit_id'] . "');";
          $linkstart    .= "jQuery('#dialogSpirit').dialog('open');";
          $linkstart    .= "return false;";
          $linkstart    .= "\">";

          $powerstart     = "<a href=\"#\" onclick=\"javascript:show_file('powers.fill.php?id="  . $a_spirits['spirit_id'] . "');";
          $powerstart    .= "show_file('active.mysql.php?update=-1&r_spirit_id=" . $a_spirits['spirit_id'] . "');";
          $powerstart    .= "show_file('powers.mysql.php?update=-1&r_spirit_id=" . $a_spirits['spirit_id'] . "');";
          $powerstart    .= "show_file('weaknesses.mysql.php?update=-1&r_spirit_id=" . $a_spirits['spirit_id'] . "');";
          $powerstart    .= "showDiv('spirit-hide');\">";

          $activestart   = "<a href=\"#\" onclick=\"javascript:show_file('active.fill.php?id="       . $a_spirits['spirit_id'] . "');jQuery('#dialogActive').dialog('open');return false;\">";
          $weaknessstart = "<a href=\"#\" onclick=\"javascript:show_file('weaknesses.fill.php?id="   . $a_spirits['spirit_id'] . "');jQuery('#dialogWeakness').dialog('open');return false;\">";
          $linkdel       = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_spirit('add.spirits.del.php?id=" . $a_spirits['spirit_id'] . "');\">";
          $linkend       = "</a>";

          $spirit_body      = return_Spirit(0, $a_spirits['spirit_body']);
          $spirit_agility   = return_Spirit(0, $a_spirits['spirit_agility']);
          $spirit_reaction  = return_Spirit(0, $a_spirits['spirit_reaction']);
          $spirit_strength  = return_Spirit(0, $a_spirits['spirit_strength']);
          $spirit_willpower = return_Spirit(0, $a_spirits['spirit_willpower']);
          $spirit_logic     = return_Spirit(0, $a_spirits['spirit_logic']);
          $spirit_intuition = return_Spirit(0, $a_spirits['spirit_intuition']);
          $spirit_charisma  = return_Spirit(0, $a_spirits['spirit_charisma']);
          $spirit_edge      = return_Spirit(0, $a_spirits['spirit_edge']);
          $spirit_essence   = return_Spirit(0, $a_spirits['spirit_essence']);
          $spirit_magic     = return_Spirit(0, $a_spirits['spirit_magic']);

          $spirit_book = return_Book($a_spirits['ver_book'], $a_spirits['spirit_page']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_spirits['spirit_id']                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $powerstart . "Select"  . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_spirits['spirit_name']         . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_body                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_agility                 . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_reaction                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_strength                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_willpower               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_logic                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_intuition               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_charisma                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . "F/2"                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_essence                 . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_magic                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_book                                 . "</td>\n";
          $output .= "</tr>\n";


          $q_string  = "select sp_act_id,sp_act_specialize,act_name ";
          $q_string .= "from sp_active ";
          $q_string .= "left join active on active.act_id = sp_active.sp_act_number ";
          $q_string .= "where sp_act_creature = " . $a_spirits['spirit_id'] . " ";
          $q_string .= "order by act_name,sp_act_specialize ";
          $q_sp_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_sp_active) > 0) {
            while ($a_sp_active = mysqli_fetch_array($q_sp_active)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('active.dialog.php?id=" . $a_sp_active['sp_act_id'] . "');jQuery('#dialogActive').dialog('open');return false;\">";
              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_spirit('add.spirits.del.php?id=" . $a_spirits['spirit_id'] . "');\">";
              $linkend   = "</a>";

              if (strlen($a_sp_active['sp_act_specialize']) > 0) {
                $active = $a_sp_active['act_name'] . " (" . $a_sp_active['sp_act_specialize'] . ")";
              } else {
                $active = $a_sp_active['act_name'];
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . $linkstart . "Active Skill: " . $active . $linkend . "</td>\n";
              $output .= "</tr>\n";
            }
          }


          $q_string  = "select sp_power_id,sp_power_specialize,sp_power_optional,pow_name ";
          $q_string .= "from sp_powers ";
          $q_string .= "left join powers on powers.pow_id = sp_powers.sp_power_number ";
          $q_string .= "where sp_power_creature = " . $a_spirits['spirit_id'] . " ";
          $q_string .= "order by sp_power_optional,pow_name,sp_power_specialize ";
          $q_sp_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_sp_powers) > 0) {
            while ($a_sp_powers = mysqli_fetch_array($q_sp_powers)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('powers.dialog.php?id=" . $a_sp_powers['sp_power_id'] . "');jQuery('#dialogPower').dialog('open');return false;\">";
              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_power('powers.del.php?id=" . $a_sp_powers['sp_power_id'] . "');\">";
              $linkend   = "</a>";

              if (strlen($a_sp_powers['sp_power_specialize']) > 0) {
                $power = $a_sp_powers['pow_name'] . " (" . $a_sp_powers['sp_power_specialize'] . ")";
              } else {
                $power = $a_sp_powers['pow_name'];
              }

              $optional = "Power: ";
              if ($a_sp_powers['sp_power_optional']) {
                $optional = "Optional Power: ";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . $linkstart . $optional . $power . $linkend . "</td>\n";
              $output .= "</tr>\n";
            }
          }


          $q_string  = "select sp_weak_id,sp_weak_specialize,weak_name ";
          $q_string .= "from sp_weaknesses ";
          $q_string .= "left join weakness on weakness.weak_id = sp_weaknesses.sp_weak_number ";
          $q_string .= "where sp_weak_creature = " . $a_spirits['spirit_id'] . " ";
          $q_string .= "order by weak_name,sp_weak_specialize ";
          $q_sp_weaknesses = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_sp_weaknesses) > 0) {
            while ($a_sp_weaknesses = mysqli_fetch_array($q_sp_weaknesses)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('weaknesses.dialog.php?id=" . $a_sp_weaknesses['sp_weak_id'] . "');jQuery('#dialogWeakness').dialog('open');return false;\">";
              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_weaknesses('weaknesses.del.php?id=" . $a_sp_weaknesses['sp_weak_id'] . "');\">";
              $linkend   = "</a>";

              if (strlen($a_sp_weaknesses['sp_weak_specialize']) > 0) {
                $weaknesses = $a_sp_weaknesses['weak_name'] . " (" . $a_sp_weaknesses['sp_weak_specialize'] . ")";
              } else {
                $weaknesses = $a_sp_weaknesses['weak_name'];
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . $linkstart . "Weakness: " . $weaknesses . $linkend . "</td>\n";
              $output .= "</tr>\n";
            }
          }


          if (strlen($a_spirits['spirit_description']) > 0) {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">Special</td>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . $a_spirits['spirit_description'] . "</td>\n";
            $output .= "</tr>\n";
          }


        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('spirits_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.spirit_name.value = '';\n";
      print "document.dialog.spirit_body.value = '';\n";
      print "document.dialog.spirit_agility.value = '';\n";
      print "document.dialog.spirit_reaction.value = '';\n";
      print "document.dialog.spirit_strength.value = '';\n";
      print "document.dialog.spirit_willpower.value = '';\n";
      print "document.dialog.spirit_logic.value = '';\n";
      print "document.dialog.spirit_intuition.value = '';\n";
      print "document.dialog.spirit_charisma.value = '';\n";
      print "document.dialog.spirit_edge.value = '';\n";
      print "document.dialog.spirit_essence.value = '';\n";
      print "document.dialog.spirit_magic.value = '';\n";
      print "document.dialog.spirit_description.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
