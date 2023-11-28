<?php
# Script: mooks.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "mooks.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select runr_name,runr_aliases,meta_name,runr_archetype,runr_archetype,meta_notes,";
  $q_string .= "runr_age,runr_sex,runr_height,runr_weight,runr_essence,runr_currentedge,runr_totaledge,";
  $q_string .= "ver_version ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "left join versions on versions.ver_id = runners.runr_version ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

  $sex = "Female";
  if ($a_runners['runr_sex']) {
    $sex = "Male";
  }

  $notoriety = 0;
  $q_string  = "select not_notoriety ";
  $q_string .= "from notoriety ";
  $q_string .= "where not_character = " . $formVars['id'] . " ";
  $q_notoriety = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_notoriety) > 0) {
    while ($a_notoriety = mysql_fetch_array($q_notoriety)) {
      $notoriety += $a_notoriety['not_notoriety'];
    }
  }

  $publicity = 0;
  $q_string  = "select pub_publicity ";
  $q_string .= "from publicity ";
  $q_string .= "where pub_character = " . $formVars['id'] . " ";
  $q_publicity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_publicity) > 0) {
    while ($a_publicity = mysql_fetch_array($q_publicity)) {
      $publicity += $a_publicity['pub_publicity'];
    }
  }

  $streetcred = 0;
  $q_string  = "select st_cred ";
  $q_string .= "from street ";
  $q_string .= "where st_character = " . $formVars['id'] . " ";
  $q_street = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_street) > 0) {
    while ($a_street = mysql_fetch_array($q_street)) {
      $streetcred += $a_streetcred['st_cred'];
    }
  }

  $currentkarma = 0;
  $totalkarma = 0;
  $q_string  = "select kar_karma ";
  $q_string .= "from karma ";
  $q_string .= "where kar_character = " . $formVars['id'] . " ";
  $q_karma = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_karma) > 0) {
    while ($a_karma = mysql_fetch_array($q_karma)) {
      if ($a_karma['kar_karma'] > 0) {
        $totalkarma += $a_karma['kar_karma'];
      }
      $currentkarma += $a_karma['kar_karma'];
    }
  }

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Personal Data</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">NAME/PRIMARY ALIAS: " . $a_runners['runr_name'] . "/" . $a_runners['runr_aliases'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Metatype: " . $a_runners['meta_name'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Archetype: " . $a_runners['runr_archetype'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Ethnicity: " . $a_runners['runr_ethnicity'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Age: " . $a_runners['runr_age'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Sex: " . $sex . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Height: " . return_Feet($a_runners['runr_height']) . "/" . return_Meters(return_Centimeters($a_runners['runr_height'])) . "m</td>";
  $output .= "  <td class=\"ui-widget-content\">Weight: " . $a_runners['runr_weight'] . " lb/" . return_Kilograms($a_runners['runr_weight']) . " kg</td>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Street Cred: " . $streetcred . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Notoriety: " . $notoriety . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Public Awareness: " . $publicity . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Current Karma: " . $currentkarma . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Total Karma: " . $totalkarma . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Essence: " . $a_runners['runr_essence'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Edge: " . $a_runners['runr_currentedge'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Total Edge: " . $a_runners['runr_totaledge'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Misc: </td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Notes: " . $a_runners['meta_notes'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";


  $q_string  = "select runr_body,runr_agility,runr_reaction,runr_strength,runr_willpower,runr_logic,";
  $q_string .= "runr_intuition,runr_charisma,runr_essence,runr_resonance,runr_magic,runr_initiate,runr_version ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Attributes Data</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Physical Attributes: </td>\n";
  $output .= "  <td class=\"ui-widget-content\">Body: " . $a_runners['runr_body'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Agility: " . $a_runners['runr_agility'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Reaction: " . $a_runners['runr_reaction'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Strength: " . $a_runners['runr_strength'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Mental Attributes: </td>\n";
  $output .= "  <td class=\"ui-widget-content\">Willpower: " . $a_runners['runr_willpower'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Logic: " . $a_runners['runr_logic'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Intuition: " . $a_runners['runr_intuition'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Charisma: " . $a_runners['runr_charisma'] . "</td>\n";
  $output .= "</tr>\n";

# first edition
  if ($a_runners['runr_version'] == 21) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# second edition
  if ($a_runners['runr_version'] == 23) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# third edition FASA
  if ($a_runners['runr_version'] == 24) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# third edition FanPro
  if ($a_runners['runr_version'] == 40) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# fourth edition FanPro
  if ($a_runners['runr_version'] == 25) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# note the 1st - 4th are copies of 20th anniversary. just need to update (if it even exists)
# 4th 20th Anniversary
  if ($a_runners['runr_version'] == 42) {
    $composure  = "WIL+CHA:138";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "INT+CHA:139";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:139";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "STR+BOD:139";
    $lftvalue   = ($a_runners['runr_strength'] + $a_runners['runr_body']);
  }

# fifth edition
  if ($a_runners['runr_version'] == 2) {
    $composure  = "CHA+WIL:152";
    $comvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_willpower']);
    $intentions = "CHA+INT:152";
    $intvalue   = ($a_runners['runr_charisma'] + $a_runners['runr_intuition']);
    $memory     = "LOG+WIL:152";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_willpower']);
    $liftcarry  = "BOD+STR:152";
    $lftvalue   = ($a_runners['runr_body'] + $a_runners['runr_strength']);
  }

# sixth world
  if ($a_runners['runr_version'] == 31) {
    $composure  = "WIL+CHA:67";
    $comvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_charisma']);
    $intentions = "WIL+INT:67";
    $intvalue   = ($a_runners['runr_willpower'] + $a_runners['runr_intuition']);
    $memory     = "LOG+INT:67";
    $memvalue   = ($a_runners['runr_logic'] + $a_runners['runr_intuition']);
    $liftcarry  = "BOD+WIL:67";
    $lftvalue   = ($a_runners['runr_body'] + $a_runners['runr_willpower']);
  }

  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Derived Attributes: </td>\n";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $composure  . "\">Composure: "        . $comvalue . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $intentions . "\">Judge Intentions: " . $intvalue . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $memory     . "\">Memory: "           . $memvalue . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\" title=\"" . $liftcarry  . "\">Lift/Carry: "       . $lftvalue . "</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  if ($a_runners['ver_version'] == 5.0) {
    $mental_limit   = ceil((($a_runners['runr_logic'] * 2)    + $a_runners['runr_intuition'] + $a_runners['runr_willpower']) /3);
    $physical_limit = ceil((($a_runners['runr_strength'] * 2) + $a_runners['runr_body']      + $a_runners['runr_reaction'])  /3);
    $social_limit   = ceil((($a_runners['runr_charisma'] * 2) + $a_runners['runr_willpower'] + $a_runners['runr_essence'])   /3);

    if ($mental_limit > $social_limit) {
      $astral_limit = $mental_limit;
    } else {
      $astral_limit = $social_limit;
    }

    $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Limits</th>";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\">Physical Limit: " . $physical_limit . "</td>\n";
    $output .= "  <td class=\"ui-widget-content\">Mental Limit: "   . $mental_limit   . "</td>\n";
    $output .= "  <td class=\"ui-widget-content\">Social Limit: "   . $social_limit   . "</td>\n";
    if ($a_runners['runr_magic'] > 0) {
      $output .= "  <td class=\"ui-widget-content\">Astral Limit: "   . $astral_limit   . "</td>\n";
    }
    $output .= "</tr>\n";
    $output .= "</table>\n";
  }

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Initiatives</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Physical Initiative</td>\n";
  $output .= "  <td class=\"ui-widget-content\">" . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";
  $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
  $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
  $output .= "</tr>\n";


  $q_string  = "select r_link_id,link_brand,link_model,link_data ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_string .= "order by link_rating,link_cost ";
  $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysql_fetch_array($q_r_commlink)) {

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Matrix Initiative: " . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . "</td>\n";

      $output .= "  <td class=\"ui-widget-content\">" . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysql_fetch_array($q_r_accessory);

        $output .= "  <td class=\"ui-widget-content\">" . "Cold-sim " . ($a_r_commlink['link_data'] + $a_runners['runr_intuition']) . " + 3d6</td>\n";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $output .= "  <td class=\"ui-widget-content\">" . "Hot-sim " . ($a_r_commlink['link_data'] + $a_runners['runr_intuition']) . " + 4d6</td>\n";
        } else {
          $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        }
      } else {
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
      }
      $output .= "</tr>\n";
    }
  }


  $q_string  = "select r_cmd_id,cmd_brand,cmd_model,cmd_data ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_string .= "order by cmd_brand,cmd_model,cmd_rating ";
  $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_command) > 0) {
    while ($a_r_command = mysql_fetch_array($q_r_command)) {

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Matrix Initiative: " . $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'] . "</td>\n";

      $output .= "  <td class=\"ui-widget-content\">" . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_command['r_cmd_id'] . " ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysql_fetch_array($q_r_accessory);

        $output .= "  <td class=\"ui-widget-content\">" . "Cold-sim " . ($a_r_command['cmd_data'] + $a_runners['runr_intuition']) . " + 3d6</td>\n";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $output .= "  <td class=\"ui-widget-content\">" . "Hot-sim " . ($a_r_command['cmd_data'] + $a_runners['runr_intuition']) . " + 4d6</td>\n";
        } else {
          $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        }
      } else {
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
      }
      $output .= "</tr>\n";
    }
  }


  $q_string  = "select r_deck_id,deck_brand,deck_model,r_deck_data ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_string .= "order by deck_brand,deck_model,deck_rating ";
  $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_cyberdeck) > 0) {
    while ($a_r_cyberdeck = mysql_fetch_array($q_r_cyberdeck)) {

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Matrix Initiative: " . $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'] . "</td>\n";

      $output .= "  <td class=\"ui-widget-content\">" . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysql_fetch_array($q_r_accessory);

        $output .= "  <td class=\"ui-widget-content\">" . "Cold-sim " . ($a_r_cyberdeck['r_deck_data'] + $a_runners['runr_intuition']) . " + 3d6</td>\n";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $output .= "  <td class=\"ui-widget-content\">" . "Hot-sim " . ($a_r_cyberdeck['r_deck_data'] + $a_runners['runr_intuition']) . " + 4d6</td>\n";
        } else {
          $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        }
      } else {
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
        $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
      }
      $output .= "</tr>\n";
    }
  }

# technomancers only have AR and VR Hot-Sim stats.
# use resonance + intuition for stats
  if ($a_runners['runr_resonance'] > 0) {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\">Matrix Initiative: Persona</td>\n";

    $output .= "  <td class=\"ui-widget-content\">" . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";

    $output .= "  <td class=\"ui-widget-content\">" . "Hot-sim " . ($a_runners['runr_resonance'] + $a_runners['runr_intuition']) . " + 4d6</td>\n";

    $output .= "  <td class=\"ui-widget-content\">" . "&nbsp;" . "</td>\n";

    $output .= "</tr>\n";
  }
  if ($a_runners['runr_magic'] > 0) {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\">Astral Initiative: " . ($a_runners['runr_intuition'] * 2) . " + 2d6</td>\n";
    $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
    $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
    $output .= "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
    $output .= "</tr>\n";
  }
  $output .= "</table>\n";

  print "document.getElementById('character_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
