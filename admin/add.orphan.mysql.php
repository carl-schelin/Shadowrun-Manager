<?php
# Script: add.orphan.mysql.php
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
    $package = "add.orphan.mysql.php";

    if (check_userlevel(1)) {

      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Orphan Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('orphan-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"orphan-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Active Skill Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Active Skill from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an Active Skill to toggle the form and edit the Active Skill.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Active Skill Management</strong> title bar to toggle the <strong>Active Skill Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Orphaned ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Table</th>\n";
      $output .= "</tr>\n";


# the process here is:
# 1. List every entry from all the Runner tables (starting with r_).
#   a. Get the item id
#   b. Get the character id
#   c. Get the item id
#   d. Get the parent id if it exists
# 2. If the character does not exist, list it to be deleted
# 3. If the original item does not exist, list it to the deleted
# 4. If the parent id does not exist, list it to be deleted


      $q_string  = "select r_acc_id,r_acc_character,r_acc_number,r_acc_parentid ";
      $q_string .= "from r_accessory ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_accessory['r_acc_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_accessory['r_acc_id'] . "&table=r_accessory&index=r_acc_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_accessory['r_acc_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_accessory['r_acc_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_accessory"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select acc_id ";
          $q_string .= "from accessory ";
          $q_string .= "where acc_id = " . $a_r_accessory['r_acc_number'] . " ";
          $q_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_accessory) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_accessory['r_acc_id'] . "&table=r_accessory&index=r_acc_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_accessory['r_acc_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_accessory['r_acc_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_accessory"                  . "</td>\n";
            $output .= "</tr>\n";
          }

##########
### Add in clearing if r_parentid is gone
##########

        }
      }


      $q_string  = "select r_act_id,r_act_character,r_act_number ";
      $q_string .= "from r_active ";
      $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_active) > 0) {
        while ($a_r_active = mysqli_fetch_array($q_r_active)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_active['r_act_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_active['r_act_id'] . "&table=r_active&index=r_act_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_active['r_act_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_active['r_act_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_active"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select act_id ";
          $q_string .= "from active ";
          $q_string .= "where act_id = " . $a_r_active['r_act_number'] . " ";
          $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_active) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_active['r_act_id'] . "&table=r_active&index=r_act_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                    . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_active['r_act_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_active['r_act_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_active"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_adp_id,r_adp_character ";
      $q_string .= "from r_adept ";
      $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_adept) > 0) {
        while ($a_r_adept = mysqli_fetch_array($q_r_adept)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_adept['r_adp_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_adept['r_adp_id'] . "&table=r_adept&index=r_adp_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_adept['r_adp_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_adept['r_adp_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_adept"                     . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


# finish this for cyberdecks and command consoles

      $q_string  = "select r_agt_id,r_agt_character,r_agt_number,r_agt_cyberdeck ";
      $q_string .= "from r_agents ";
      $q_r_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_agents) > 0) {
        while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_agents['r_agt_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_agents['r_agt_id'] . "&table=r_agents&index=r_agt_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_agents"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select agt_id ";
          $q_string .= "from agents ";
          $q_string .= "where agt_id = " . $a_r_agents['r_agt_number'] . " ";
          $q_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_agents) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_agents['r_agt_id'] . "&table=r_agents&index=r_agt_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                    . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_agents"                  . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select r_deck_id ";
          $q_string .= "from r_cyberdeck ";
          $q_string .= "where r_deck_id = " . $a_r_agents['r_agt_cyberdeck'] . " ";
          $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_cyberdeck) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_agents['r_agt_id'] . "&table=r_agents&index=r_agt_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_agents['r_agt_cyberdeck'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_agents"                     . "</td>\n";
            $output .= "</tr>\n";
          }

        }
      }


      $q_string  = "select r_alc_id,r_alc_character,r_alc_number ";
      $q_string .= "from r_alchemy ";
      $q_r_alchemy = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_alchemy) > 0) {
        while ($a_r_alchemy = mysqli_fetch_array($q_r_alchemy)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_alchemy['r_alc_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_alchemy['r_alc_id'] . "&table=r_alchemy&index=r_alc_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_alchemy['r_alc_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_alchemy['r_alc_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_alchemy"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select spell_id ";
          $q_string .= "from spells ";
          $q_string .= "where spell_id = " . $a_r_alchemy['r_alc_number'] . " ";
          $q_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_spells) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_alchemy['r_alc_id'] . "&table=r_alchemy&index=r_alc_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_alchemy['r_alc_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_alchemy['r_alc_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_alchemy"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_ammo_id,r_ammo_character,r_ammo_number ";
      $q_string .= "from r_ammo ";
      $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_ammo['r_ammo_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_ammo['r_ammo_id'] . "&table=r_ammo&index=r_ammo_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_ammo['r_ammo_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_ammo['r_ammo_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_ammo"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select ammo_id ";
          $q_string .= "from ammo ";
          $q_string .= "where ammo_id = " . $a_r_ammo['r_ammo_number'] . " ";
          $q_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_ammo) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_ammo['r_ammo_id'] . "&table=r_ammo&index=r_ammo_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_ammo['r_ammo_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_ammo['r_ammo_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_ammo"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_arm_id,r_arm_character,r_arm_number ";
      $q_string .= "from r_armor ";
      $q_r_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_armor) > 0) {
        while ($a_r_armor = mysqli_fetch_array($q_r_armor)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_armor['r_arm_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_armor['r_arm_id'] . "&table=r_armor&index=r_arm_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_armor['r_arm_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_armor['r_arm_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_armor"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select arm_id ";
          $q_string .= "from armor ";
          $q_string .= "where arm_id = " . $a_r_armor['r_arm_number'] . " ";
          $q_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_armor) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_armor['r_arm_id'] . "&table=r_armor&index=r_arm_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_armor['r_arm_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_armor['r_arm_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_armor"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_bio_id,r_bio_character,r_bio_number ";
      $q_string .= "from r_bioware ";
      $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_bioware) > 0) {
        while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_bioware['r_bio_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_bioware['r_bio_id'] . "&table=r_bioware&index=r_bio_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_bioware['r_bio_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_bioware['r_bio_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_bioware"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select bio_id ";
          $q_string .= "from bioware ";
          $q_string .= "where bio_id = " . $a_r_bioware['r_bio_number'] . " ";
          $q_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_bioware) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_bioware['r_bio_id'] . "&table=r_bioware&index=r_bio_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_bioware['r_bio_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_bioware['r_bio_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_bioware"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_cmd_id,r_cmd_character,r_cmd_number ";
      $q_string .= "from r_command ";
      $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_command) > 0) {
        while ($a_r_command = mysqli_fetch_array($q_r_command)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_command['r_cmd_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_command['r_cmd_id'] . "&table=r_command&index=r_cmd_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_command"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select cmd_id ";
          $q_string .= "from command ";
          $q_string .= "where cmd_id = " . $a_r_command['r_cmd_number'] . " ";
          $q_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_command) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_command['r_cmd_id'] . "&table=r_command&index=r_cmd_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_command['r_cmd_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_command"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_link_id,r_link_character,r_link_number ";
      $q_string .= "from r_commlink ";
      $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_commlink) > 0) {
        while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_commlink['r_link_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_commlink['r_link_id'] . "&table=r_commlink&index=r_link_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_commlink"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select link_id ";
          $q_string .= "from commlink ";
          $q_string .= "where link_id = " . $a_r_commlink['r_link_number'] . " ";
          $q_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_commlink) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_commlink['r_link_id'] . "&table=r_commlink&index=r_link_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_commlink"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_form_id,r_form_character,r_form_number ";
      $q_string .= "from r_complexform ";
      $q_r_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_complexform) > 0) {
        while ($a_r_complexform = mysqli_fetch_array($q_r_complexform)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_complexform['r_form_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_complexform['r_form_id'] . "&table=r_complexform&index=r_form_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                             . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_complexform['r_form_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_complexform['r_form_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_complexform"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select form_id ";
          $q_string .= "from complexform ";
          $q_string .= "where form_id = " . $a_r_complexform['r_form_number'] . " ";
          $q_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_complexform) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_complexform['r_form_id'] . "&table=r_complexform&index=r_form_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_complexform['r_form_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_complexform['r_form_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_complexform"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_con_id,r_con_character,r_con_number ";
      $q_string .= "from r_contact ";
      $q_r_contact = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_contact) > 0) {
        while ($a_r_contact = mysqli_fetch_array($q_r_contact)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_contact['r_con_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_contact['r_con_id'] . "&table=r_contact&index=r_con_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_contact['r_con_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_contact['r_con_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_contact"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select con_id ";
          $q_string .= "from contact ";
          $q_string .= "where con_id = " . $a_r_contact['r_con_number'] . " ";
          $q_contact = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_contact) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_contact['r_con_id'] . "&table=r_contact&index=r_con_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_contact['r_con_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_contact['r_con_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_contact"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_deck_id,r_deck_character,r_deck_number ";
      $q_string .= "from r_cyberdeck ";
      $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_cyberdeck) > 0) {
        while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_cyberdeck['r_deck_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_cyberdeck['r_deck_id'] . "&table=r_cyberdeck&index=r_deck_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_cyberdeck"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select deck_id ";
          $q_string .= "from cyberdeck ";
          $q_string .= "where deck_id = " . $a_r_cyberdeck['r_deck_number'] . " ";
          $q_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_cyberdeck) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_cyberdeck['r_deck_id'] . "&table=r_cyberdeck&index=r_deck_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_cyberdeck"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_ware_id,r_ware_character,r_ware_number ";
      $q_string .= "from r_cyberware ";
      $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_cyberware) > 0) {
        while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_cyberware['r_ware_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_cyberware['r_ware_id'] . "&table=r_cyberware&index=r_ware_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberware['r_ware_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberware['r_ware_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_cyberware"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select ware_id ";
          $q_string .= "from cyberware ";
          $q_string .= "where ware_id = " . $a_r_cyberware['r_ware_character'] . " ";
          $q_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_cyberware) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_cyberware['r_ware_id'] . "&table=r_cyberware&index=r_ware_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberware['r_ware_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberware['r_ware_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_cyberware"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_fa_id,r_fa_character,r_fa_number ";
      $q_string .= "from r_firearms ";
      $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_firearms) > 0) {
        while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_firearms['r_fa_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_firearms['r_fa_id'] . "&table=r_firearms&index=r_fa_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_firearms['r_fa_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_firearms['r_fa_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_firearms"                    . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select fa_id ";
          $q_string .= "from firearms ";
          $q_string .= "where fa_id = " . $a_r_firearms['r_fa_number'] . " ";
          $q_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_firearms) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_firearms['r_fa_id'] . "&table=r_firearms&index=r_fa_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_firearms['r_fa_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_firearms['r_fa_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_firearms"                 . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_gear_id,r_gear_character,r_gear_number ";
      $q_string .= "from r_gear ";
      $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_gear) > 0) {
        while ($a_r_gear = mysqli_fetch_array($q_r_gear)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_gear['r_gear_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_gear['r_gear_id'] . "&table=r_gear&index=r_gear_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_gear['r_gear_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_gear['r_gear_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_gear"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select gear_id ";
          $q_string .= "from gear ";
          $q_string .= "where gear_id = " . $a_r_gear['r_gear_number'] . " ";
          $q_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_gear) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_gear['r_gear_id'] . "&table=r_gear&index=r_gear_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                   . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_gear['r_gear_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_gear['r_gear_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_gear"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select id_id,id_character ";
      $q_string .= "from r_identity ";
      $q_r_identity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_identity) > 0) {
        while ($a_r_identity = mysqli_fetch_array($q_r_identity)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_identity['id_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_identity['id_id'] . "&table=r_identity&index=id_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_identity['id_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_identity['id_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_identity"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_know_id,r_know_character,r_know_number ";
      $q_string .= "from r_knowledge ";
      $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_knowledge) > 0) {
        while ($a_r_knowledge = mysqli_fetch_array($q_r_knowledge)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_knowledge['r_know_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_knowledge['r_know_id'] . "&table=r_knowledge&index=r_know_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_knowledge['r_know_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_knowledge['r_know_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_knowledge"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select know_id ";
          $q_string .= "from knowledge ";
          $q_string .= "where know_id = " . $a_r_knowledge['r_know_number'] . " ";
          $q_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_knowledge) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_knowledge['r_know_id'] . "&table=r_knowledge&index=r_know_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_knowledge['r_know_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_knowledge['r_know_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_knowledge"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_lang_id,r_lang_character,r_lang_number ";
      $q_string .= "from r_language ";
      $q_r_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_language) > 0) {
        while ($a_r_language = mysqli_fetch_array($q_r_language)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_language['r_lang_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_language['r_lang_id'] . "&table=r_language&index=r_lang_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_language['r_lang_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_language['r_lang_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_language"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select lang_id ";
          $q_string .= "from language ";
          $q_string .= "where lang_id = " . $a_r_language['r_lang_number'] . " ";
          $q_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_language) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_language['r_lang_id'] . "&table=r_language&index=r_lang_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_language['r_lang_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_language['r_lang_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_language"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select lic_id,lic_character,lic_identity ";
      $q_string .= "from r_license ";
      $q_r_license = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_license) > 0) {
        while ($a_r_license = mysqli_fetch_array($q_r_license)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_license['lic_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_license['lic_id'] . "&table=r_license&index=lic_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_license['lic_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_license['lic_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_license"                   . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select id_id ";
          $q_string .= "from r_identity ";
          $q_string .= "where id_id = " . $a_r_license['lic_identity'] . " ";
          $q_identity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_identity) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_license['lic_id'] . "&table=r_license&index=lic_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_license['lic_id']       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_license['lic_identity'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_license"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_life_id,r_life_character,r_life_number ";
      $q_string .= "from r_lifestyle ";
      $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_lifestyle) > 0) {
        while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_lifestyle['r_life_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_lifestyle['r_life_id'] . "&table=r_lifestyle&index=r_life_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_lifestyle['r_life_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_lifestyle['r_life_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_lifestyle"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select life_id ";
          $q_string .= "from lifestyle ";
          $q_string .= "where life_id = " . $a_r_lifestyle['r_life_number'] . " ";
          $q_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_lifestyle) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_lifestyle['r_life_id'] . "&table=r_lifestyle&index=r_life_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_lifestyle['r_life_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_lifestyle['r_life_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_lifestyle"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_melee_id,r_melee_character,r_melee_number ";
      $q_string .= "from r_melee ";
      $q_r_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_melee) > 0) {
        while ($a_r_melee = mysqli_fetch_array($q_r_melee)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_melee['r_melee_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_melee['r_melee_id'] . "&table=r_melee&index=r_melee_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_melee['r_melee_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_melee['r_melee_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_melee"                       . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select melee_id ";
          $q_string .= "from melee ";
          $q_string .= "where melee_id = " . $a_r_melee['r_melee_number'] . " ";
          $q_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_melee) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_melee['r_melee_id'] . "&table=r_melee&index=r_melee_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_melee['r_melee_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_melee['r_melee_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_melee"                    . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_mentor_id,r_mentor_character,r_mentor_number ";
      $q_string .= "from r_mentor ";
      $q_r_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_mentor) > 0) {
        while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_mentor['r_mentor_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_mentor['r_mentor_id'] . "&table=r_mentor&index=r_mentor_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_mentor['r_mentor_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_mentor['r_mentor_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_mentor"                        . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select mentor_id ";
          $q_string .= "from mentor ";
          $q_string .= "where mentor_id = " . $a_r_mentor['r_mentor_number'] . " ";
          $q_mentor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_mentor) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_mentor['r_mentor_id'] . "&table=r_mentor&index=r_mentor_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_mentor['r_mentor_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_mentor['r_mentor_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_mentor"                     . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_pgm_id,r_pgm_character,r_pgm_number,r_pgm_cyberdeck,r_pgm_command ";
      $q_string .= "from r_program ";
      $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_program) > 0) {
        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_program['r_pgm_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_program['r_pgm_id'] . "&table=r_program&index=r_pgm_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_program"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select pgm_id ";
          $q_string .= "from program ";
          $q_string .= "where pgm_id = " . $a_r_program['r_pgm_number'] . " ";
          $q_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_program) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_program['r_pgm_id'] . "&table=r_program&index=r_pgm_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_program"                  . "</td>\n";
            $output .= "</tr>\n";
          }

          if ($a_r_program['r_pgm_cyberdeck'] > 0) {
            $q_string  = "select r_deck_id ";
            $q_string .= "from r_cyberdeck ";
            $q_string .= "where r_deck_id = " . $a_r_program['r_pgm_cyberdeck'] . " ";
            $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_cyberdeck) == 0) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_program['r_pgm_id'] . "&table=r_program&index=r_pgm_id');\">";

              $output .= "<tr>\n";
              $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_id']        . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_cyberdeck'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . "r_program"                     . "</td>\n";
              $output .= "</tr>\n";
            }
          }

          if ($a_r_program['r_pgm_command'] > 0) {
            $q_string  = "select r_cmd_id ";
            $q_string .= "from r_command ";
            $q_string .= "where r_cmd_id = " . $a_r_program['r_pgm_command'] . " ";
            $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_command) == 0) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_program['r_pgm_id'] . "&table=r_program&index=r_pgm_id');\">";

              $output .= "<tr>\n";
              $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_id']      . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_program['r_pgm_command'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . "r_program"                   . "</td>\n";
              $output .= "</tr>\n";
            }
          }
        }
      }


      $q_string  = "select r_proj_id,r_proj_character,r_proj_number ";
      $q_string .= "from r_projectile ";
      $q_r_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_projectile) > 0) {
        while ($a_r_projectile = mysqli_fetch_array($q_r_projectile)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_projectile['r_proj_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_projectile['r_proj_id'] . "&table=r_projectile&index=r_proj_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_projectile['r_proj_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_projectile['r_proj_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_projectile"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select proj_id ";
          $q_string .= "from projectile ";
          $q_string .= "where proj_id = " . $a_r_projectile['r_proj_number'] . " ";
          $q_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_projectile) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_projectile['r_proj_id'] . "&table=r_projectile&index=r_proj_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_projectile['r_proj_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_projectile['r_proj_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_projectile"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_qual_id,r_qual_character,r_qual_number ";
      $q_string .= "from r_qualities ";
      $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_qualities) > 0) {
        while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_qualities['r_qual_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_qualities['r_qual_id'] . "&table=r_qualities&index=r_qual_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_qualities['r_qual_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_qualities['r_qual_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_qualities"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select qual_id ";
          $q_string .= "from qualities ";
          $q_string .= "where qual_id = " . $a_r_qualities['r_qual_number'] . " ";
          $q_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_qualities) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_qualities['r_qual_id'] . "&table=r_qualities&index=r_qual_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_qualities['r_qual_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_qualities['r_qual_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_qualities"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_spell_id,r_spell_character,r_spell_number ";
      $q_string .= "from r_spells ";
      $q_r_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_spells) > 0) {
        while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_spells['r_spell_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_spells['r_spell_id'] . "&table=r_spells&index=r_spell_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spells['r_spell_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spells['r_spell_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_spells"                       . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select spell_id ";
          $q_string .= "from spells ";
          $q_string .= "where spell_id = " . $a_r_spells['r_spell_number'] . " ";
          $q_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_spells) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_spells['r_spell_id'] . "&table=r_spells&index=r_spell_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spells['r_spell_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spells['r_spell_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_spells"                    . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_spirit_id,r_spirit_character,r_spirit_number ";
      $q_string .= "from r_spirit ";
      $q_r_spirit = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_spirit) > 0) {
        while ($a_r_spirit = mysqli_fetch_array($q_r_spirit)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_spirit['r_spirit_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_spirit['r_spirit_id'] . "&table=r_spirit&index=r_spirit_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_spirit"                        . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select spirit_id ";
          $q_string .= "from spirits ";
          $q_string .= "where spirit_id = " . $a_r_spirit['r_spirit_number'] . " ";
          $q_spirits = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_spirits) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_spirit['r_spirit_id'] . "&table=r_spirit&index=r_spirit_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_spirit['r_spirit_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_spirit"                     . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_sprite_id,r_sprite_character,r_sprite_number ";
      $q_string .= "from r_sprite ";
      $q_r_sprite = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_sprite) > 0) {
        while ($a_r_sprite = mysqli_fetch_array($q_r_sprite)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_sprite['r_sprite_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_sprite['r_sprite_id'] . "&table=r_sprite&index=r_sprite_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_sprite"                        . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select sprite_id ";
          $q_string .= "from sprites ";
          $q_string .= "where sprite_id = " . $a_r_sprite['r_sprite_number'] . " ";
          $q_sprites = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sprites) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_sprite['r_sprite_id'] . "&table=r_sprite&index=r_sprite_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_sprite['r_sprite_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_sprite"                     . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_trad_id,r_trad_character,r_trad_number ";
      $q_string .= "from r_tradition ";
      $q_r_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_tradition) > 0) {
        while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_tradition['r_trad_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_tradition['r_trad_id'] . "&table=r_tradition&index=r_trad_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_tradition['r_trad_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_tradition['r_trad_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_tradition"                      . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select trad_id ";
          $q_string .= "from tradition ";
          $q_string .= "where trad_id = " . $a_r_tradition['r_trad_number'] . " ";
          $q_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_tradition) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_tradition['r_trad_id'] . "&table=r_tradition&index=r_trad_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_tradition['r_trad_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_tradition['r_trad_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_tradition"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select r_veh_id,r_veh_character,r_veh_number ";
      $q_string .= "from r_vehicles ";
      $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_vehicles) > 0) {
        while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_r_vehicles['r_veh_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_vehicles['r_veh_id'] . "&table=r_vehicles&index=r_veh_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                         . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['r_veh_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['r_veh_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_vehicles"                     . "</td>\n";
            $output .= "</tr>\n";
          }

          $q_string  = "select veh_id ";
          $q_string .= "from vehicles ";
          $q_string .= "where veh_id = " . $a_r_vehicles['r_veh_number'] . " ";
          $q_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_vehicles) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_r_vehicles['r_veh_id'] . "&table=r_vehicles&index=r_veh_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['r_veh_id']     . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_vehicles['r_veh_number'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "r_vehicles"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select his_id,his_character ";
      $q_string .= "from history ";
      $q_history = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_history) > 0) {
        while ($a_history = mysqli_fetch_array($q_history)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_history['his_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_history['his_id'] . "&table=history&index=his_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                    . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_history['his_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_history['his_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "history"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select kar_id,kar_character ";
      $q_string .= "from karma ";
      $q_karma = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_karma) > 0) {
        while ($a_karma = mysqli_fetch_array($q_karma)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_karma['kar_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_karma['kar_id'] . "&table=karma&index=kar_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_karma['kar_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_karma['kar_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "karma"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select st_id,st_character ";
      $q_string .= "from street ";
      $q_street = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_street) > 0) {
        while ($a_street = mysqli_fetch_array($q_street)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_street['st_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_street['st_id'] . "&table=street&index=st_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_street['st_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_street['st_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "street"                  . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select not_id,not_character ";
      $q_string .= "from notoriety ";
      $q_notoriety = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_notoriety) > 0) {
        while ($a_notoriety = mysqli_fetch_array($q_notoriety)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_notoriety['not_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_notoriety['not_id'] . "&table=notoriety&index=not_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_notoriety['not_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_notoriety['not_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "notoriety"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }


      $q_string  = "select pub_id,pub_character ";
      $q_string .= "from publicity ";
      $q_publicity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_publicity) > 0) {
        while ($a_publicity = mysqli_fetch_array($q_publicity)) {

          $q_string  = "select runr_name ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $a_publicity['pub_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) == 0) {

            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_orphan('add.orphan.del.php?id=" . $a_publicity['pub_id'] . "&table=publicity&index=pub_id');\">";

            $output .= "<tr>\n";
            $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_publicity['pub_id']        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_publicity['pub_character'] . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . "publicity"                   . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      }

      $output .= "</table>\n";

      print "document.getElementById('orphan_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
