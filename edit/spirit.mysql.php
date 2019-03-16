<?php
# Script: spirit.mysql.php
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
    $package = "spirit.mysql.php";
    $formVars['update']                 = clean($_GET['update'],                10);
    $formVars['r_spirit_character']     = clean($_GET['r_spirit_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_spirit_id']            = clean($_GET['id'],                  10);
        $formVars['r_spirit_number']        = clean($_GET['r_spirit_number'],     10);
        $formVars['r_spirit_force']         = clean($_GET['r_spirit_force'],      10);
        $formVars['r_spirit_services']      = clean($_GET['r_spirit_services'],   10);
        $formVars['r_spirit_bound']         = clean($_GET['r_spirit_bound'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_spirit_force'] == '') {
          $formVars['r_spirit_force'] = 0;
        }
        if ($formVars['r_spirit_services'] == '') {
          $formVars['r_spirit_services'] = 0;
        }
        if ($formVars['r_spirit_bound'] == 'true') {
          $formVars['r_spirit_bound'] = 1;
        } else {
          $formVars['r_spirit_bound'] = 0;
        }

        if ($formVars['r_spirit_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_spirit_character   =   " . $formVars['r_spirit_character']   . "," .
            "r_spirit_number      =   " . $formVars['r_spirit_number']      . "," .
            "r_spirit_force       =   " . $formVars['r_spirit_force']       . "," . 
            "r_spirit_services    =   " . $formVars['r_spirit_services']    . "," . 
            "r_spirit_bound       =   " . $formVars['r_spirit_bound'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_spirit set r_spirit_id = NULL," . $q_string;
            $message = "Spirit summoned.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_spirit set " . $q_string . " where r_spirit_id = " . $formVars['r_spirit_id'];
            $message = "Spirit reinforced.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_spirit_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

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
        $output .= "<input type=\"button\" name=\"r_spirit_refresh\" value=\"Refresh My Spirit Listing\" onClick=\"javascript:attach_spirit('spirit.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_spirit_update\"  value=\"Update Spirit\"          onClick=\"javascript:attach_spirit('spirit.mysql.php', 1);hideDiv('spirit-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_spirit_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_spirit_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Active Sprite Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Spirit: <span id=\"r_spirit_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Force: <input type=\"text\" name=\"r_spirit_force\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Services: <input type=\"text\" name=\"r_spirit_services\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Bound: <input type=\"checkbox\" name=\"r_spirit_bound\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('spirits_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Spirit Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spirit-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"spirit-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Spirit Name</th>\n";
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
        $q_string .= "spirit_strength,spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,";
        $q_string .= "spirit_edge,spirit_essence,spirit_magic,spirit_description,ver_book,spirit_page ";
        $q_string .= "from spirits ";
        $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by spirit_name ";
        $q_spirits = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_spirits) > 0) {
          while ($a_spirits = mysql_fetch_array($q_spirits)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('spirit.mysql.php?update=0&r_spirit_character=" . $formVars['r_spirit_character'] . "&r_spirit_number=" . $a_spirits['spirit_id'] . "');\">";
            $linkend = "</a>";

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
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_spirits['spirit_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_body            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_agility         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_reaction        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_strength        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_willpower       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_logic           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_intuition       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_charisma        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_edge            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_essence         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_magic           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_book                         . "</td>\n";
            $output .= "</tr>\n";


            $active = "Active Skills: ";
            $a_comma = "";
            $q_string  = "select sp_act_id,sp_act_specialize,act_name,ver_book,act_page ";
            $q_string .= "from sp_active ";
            $q_string .= "left join active on active.act_id = sp_active.sp_act_number ";
            $q_string .= "left join versions on versions.ver_id = active.act_book ";
            $q_string .= "where sp_act_creature = " . $a_spirits['spirit_id'] . " ";
            $q_string .= "order by act_name,sp_act_specialize ";
            $q_sp_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_sp_active) > 0) {
              while ($a_sp_active = mysql_fetch_array($q_sp_active)) {

                if (strlen($a_sp_active['sp_act_specialize']) > 0) {
                  $active .= $a_comma . $a_sp_active['act_name'] . " (" . $a_sp_active['sp_act_specialize'] . ")";
                  $a_comma = ", ";
                } else {
                  $active .= $a_comma . $a_sp_active['act_name'];
                  $a_comma = ", ";
                }

                $active_book = return_Book($a_sp_active['ver_book'], $a_sp_active['act_page']);

              }
              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">" . $active . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $active_book . "</td>\n";
              $output .= "</tr>\n";
            }


            $powers = "Powers: ";
            $p_comma = "";
            $optional = "Optional Powers: ";
            $o_comma = "";
            $q_string  = "select sp_power_id,sp_power_specialize,sp_power_optional,pow_name,ver_book,pow_page ";
            $q_string .= "from sp_powers ";
            $q_string .= "left join powers on powers.pow_id = sp_powers.sp_power_number ";
            $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
            $q_string .= "where sp_power_creature = " . $a_spirits['spirit_id'] . " ";
            $q_string .= "order by sp_power_optional,pow_name,sp_power_specialize ";
            $q_sp_powers = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_sp_powers) > 0) {
              while ($a_sp_powers = mysql_fetch_array($q_sp_powers)) {

                $specialize = $a_sp_powers['pow_name'];
                if (strlen($a_sp_powers['sp_power_specialize']) > 0) {
                  $specialize = $a_sp_powers['pow_name'] . " (" . $a_sp_powers['sp_power_specialize'] . ")";
                }

                if ($a_sp_powers['sp_power_optional']) {
                  $optional .= $o_comma . $specialize;
                  $o_comma = ", ";
                } else {
                  $powers .= $p_comma . $specialize;
                  $p_comma = ", ";
                }

                $power_book = return_Book($a_sp_powers['ver_book'], $a_sp_powers['pow_page']);

              }
              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">" . $powers . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
              $output .= "</tr>\n";
              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">" . $optional . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
              $output .= "</tr>\n";
            }


            $weaknesses = "Weaknesses: ";
            $comma = "";
            $q_string  = "select sp_weak_id,sp_weak_specialize,weak_name,ver_book,weak_page ";
            $q_string .= "from sp_weaknesses ";
            $q_string .= "left join weakness on weakness.weak_id = sp_weaknesses.sp_weak_number ";
            $q_string .= "left join versions on versions.ver_id = weakness.weak_book ";
            $q_string .= "where sp_weak_creature = " . $a_spirits['spirit_id'] . " ";
            $q_string .= "order by weak_name,sp_weak_specialize ";
            $q_sp_weaknesses = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_sp_weaknesses) > 0) {
              while ($a_sp_weaknesses = mysql_fetch_array($q_sp_weaknesses)) {

                if (strlen($a_sp_weaknesses['sp_weak_specialize']) > 0) {
                  $weaknesses .= $comma . $a_sp_weaknesses['weak_name'] . " (" . $a_sp_weaknesses['sp_weak_specialize'] . ")";
                  $comma = ", ";
                } else {
                  $weaknesses .= $a_sp_weaknesses['weak_name'];
                  $comma = ", ";
                }

                $weakness_book = return_Book($a_sp_weaknesses['ver_book'], $a_sp_weaknesses['weak_page']);

                $output .= "<tr>\n";
                $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">" . $weaknesses . "</td>\n";
                $output .= "  <td class=\"ui-widget-content delete\">" . $weakness_book . "</td>\n";
                $output .= "</tr>\n";
              }
            }


            if (strlen($a_spirits['spirit_description']) > 0) {
              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"17\">Special: " . $a_spirits['spirit_description'] . "</td>\n";
              $output .= "</tr>\n";
            }

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">No Spirits added.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_spirit);

        print "document.getElementById('spirits_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Spirit Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spirit-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"spirit-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Spirit Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Force</th>\n";
      $output .=   "<th class=\"ui-state-default\">Services</th>\n";
      $output .=   "<th class=\"ui-state-default\">Bound</th>\n";
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

      $q_string  = "select spirit_id,r_spirit_id,r_spirit_force,r_spirit_services,r_spirit_bound,";
      $q_string .= "spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
      $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,";
      $q_string .= "spirit_edge,spirit_essence,spirit_magic,spirit_description,ver_book,spirit_page ";
      $q_string .= "from r_spirit ";
      $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
      $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
      $q_string .= "where r_spirit_character = " . $formVars['r_spirit_character'] . " ";
      $q_string .= "order by spirit_name ";
      $q_r_spirit = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_spirit) > 0) {
        while ($a_r_spirit = mysql_fetch_array($q_r_spirit)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('spirit.fill.php?id=" . $a_r_spirit['r_spirit_id'] . "');showDiv('spirit-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_spirit('spirit.del.php?id="  . $a_r_spirit['r_spirit_id'] . "');\">";
          $linkend   = "</a>";

          $spirit_body      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_body']);
          $spirit_agility   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_agility']);
          $spirit_reaction  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_reaction']);
          $spirit_strength  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_strength']);
          $spirit_willpower = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_willpower']);
          $spirit_logic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_logic']);
          $spirit_intuition = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_intuition']);
          $spirit_charisma  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_charisma']);
          $spirit_edge      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_edge']);
          $spirit_essence   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_essence']);
          $spirit_magic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_magic']);

          $spirit_book = return_Book($a_r_spirit['ver_book'], $a_r_spirit['spirit_page']);

          if ($a_r_spirit['r_spirit_bound']) {
            $bound = 'Yes';
          } else {
            $bound = 'No';
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                           . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_spirit['spirit_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_force']         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_services']      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $bound                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_body                          . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_agility                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_reaction                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_strength                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_willpower                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_logic                         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_intuition                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_charisma                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_edge                          . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_essence                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_magic                         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $spirit_book                          . "</td>\n";
          $output .= "</tr>\n";


          $active = "Active Skills (Dice Pool): ";
          $a_comma = "";
          $q_string  = "select sp_act_specialize,act_name,att_column,ver_book,act_page ";
          $q_string .= "from sp_active ";
          $q_string .= "left join active on active.act_id = sp_active.sp_act_number ";
          $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
          $q_string .= "left join versions on versions.ver_id = active.act_book ";
          $q_string .= "where sp_act_creature = " . $a_r_spirit['spirit_id'] . " ";
          $q_string .= "order by act_name,sp_act_specialize ";
          $q_sp_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sp_active) > 0) {
            while ($a_sp_active = mysql_fetch_array($q_sp_active)) {

              if ($a_sp_active['att_column'] == "runr_body") {
                $att_column = "spirit_body";
              }
              if ($a_sp_active['att_column'] == "runr_agility") {
                $att_column = "spirit_agility";
              }
              if ($a_sp_active['att_column'] == "runr_reaction") {
                $att_column = "spirit_reaction";
              }
              if ($a_sp_active['att_column'] == "runr_strength") {
                $att_column = "spirit_strength";
              }
              if ($a_sp_active['att_column'] == "runr_willpower") {
                $att_column = "spirit_willpower";
              }
              if ($a_sp_active['att_column'] == "runr_logic") {
                $att_column = "spirit_logic";
              }
              if ($a_sp_active['att_column'] == "runr_intuition") {
                $att_column = "spirit_intuition";
              }
              if ($a_sp_active['att_column'] == "runr_charisma") {
                $att_column = "spirit_charisma";
              }

              $q_string  = "select " . $att_column . " ";
              $q_string .= "from spirits ";
              $q_string .= "where spirit_id = " . $a_r_spirit['spirit_id'] . " ";
              $q_spirits = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              $a_spirits = mysql_fetch_array($q_spirits);

              $specialize = $a_sp_active['act_name'];
              if (strlen($a_sp_active['sp_act_specialize']) > 0) {
                $specialize = $a_sp_active['act_name'] . " (" . $a_sp_active['sp_act_specialize'] . ")";
              }

              if ($a_r_spirit['r_spirit_force'] == 0) {
                $active .= $a_comma . $specialize . " (F+(" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . "))";
              } else {
                $active .= $a_comma . $specialize . " (" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . ")";
              }
              $a_comma = ", ";

              $active_book = return_Book($a_sp_active['ver_book'], $a_sp_active['act_page']);

            }
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">" . $active . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">" . $active_book . "</td>\n";
            $output .= "</tr>\n";
          }


          $powers = "Powers: ";
          $p_comma = "";
          $optional = "Optional Powers: ";
          $o_comma = "";
          $q_string  = "select sp_power_id,sp_power_specialize,sp_power_optional,pow_name,ver_book,pow_page ";
          $q_string .= "from sp_powers ";
          $q_string .= "left join powers on powers.pow_id = sp_powers.sp_power_number ";
          $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
          $q_string .= "where sp_power_creature = " . $a_r_spirit['spirit_id'] . " ";
          $q_string .= "order by sp_power_optional,pow_name,sp_power_specialize ";
          $q_sp_powers = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sp_powers) > 0) {
            while ($a_sp_powers = mysql_fetch_array($q_sp_powers)) {

              $specialize = $a_sp_powers['pow_name'];
              if (strlen($a_sp_powers['sp_power_specialize']) > 0) {
                $specialize = $a_sp_powers['pow_name'] . " (" . $a_sp_powers['sp_power_specialize'] . ")";
              }

              if ($a_sp_powers['sp_power_optional']) {
                $optional .= $o_comma . $specialize;
                $o_comma = ", ";
              } else {
                $powers .= $p_comma . $specialize;
                $p_comma = ", ";
              }

              $power_book = return_Book($a_sp_powers['ver_book'], $a_sp_powers['pow_page']);
            }
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">" . $powers . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">" . $optional . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
            $output .= "</tr>\n";
          }


          $weaknesses = "Weaknesses: ";
          $comma = "";
          $q_string  = "select sp_weak_id,sp_weak_specialize,weak_name,ver_book,weak_page ";
          $q_string .= "from sp_weaknesses ";
          $q_string .= "left join weakness on weakness.weak_id = sp_weaknesses.sp_weak_number ";
          $q_string .= "left join versions on versions.ver_id = weakness.weak_book ";
          $q_string .= "where sp_weak_creature = " . $a_r_spirit['spirit_id'] . " ";
          $q_string .= "order by weak_name,sp_weak_specialize ";
          $q_sp_weaknesses = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sp_weaknesses) > 0) {
            while ($a_sp_weaknesses = mysql_fetch_array($q_sp_weaknesses)) {

              if (strlen($a_sp_weaknesses['sp_weak_specialize']) > 0) {
                $weaknesses .= $comma . $a_sp_weaknesses['weak_name'] . " (" . $a_sp_weaknesses['sp_weak_specialize'] . ")";
                $comma = ", ";
              } else {
                $weaknesses .= $a_sp_weaknesses['weak_name'];
                $comma = ", ";
              }

              $weakness_book = return_Book($a_sp_weaknesses['ver_book'], $a_sp_weaknesses['weak_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content\" colspan=\"16\">" . $weaknesses . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $weakness_book . "</td>\n";
              $output .= "</tr>\n";
            }
          }


          if (strlen($a_r_spirit['spirit_description']) > 0) {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"17\">Special: " . $a_r_spirit['spirit_description'] . "</td>\n";
            $output .= "</tr>\n";
          }

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"17\">No Spirits added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_spirit);

      print "document.getElementById('my_spirits_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
