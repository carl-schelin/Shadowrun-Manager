<?php
# Script: pregen.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "pregen.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select meta_name,runr_archetype,runr_essence,runr_totaledge,runr_body,";
  $q_string .= "runr_agility,runr_reaction,runr_strength,runr_willpower,runr_logic,runr_sex,";
  $q_string .= "runr_intuition,runr_charisma,runr_resonance,runr_magic,runr_initiate,ver_version ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "left join versions on versions.ver_id = runners.runr_version ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_runners = mysqli_fetch_array($q_runners);

  $sex = "Female";
  if ($a_runners['runr_sex']) {
    $sex = "Male";
  }

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Metatype: " . $a_runners['meta_name'] . "</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">B</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">A</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">R</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">S</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">W</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">L</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">I</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">C</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">ESS</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"10%\">EDG</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_body'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_agility'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_reaction'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_strength'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_willpower'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_logic'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_intuition'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_charisma'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_essence'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners['runr_totaledge'] . "</td>\n";
  $output .= "</tr>\n";

  $physical_damage = ceil(($a_runners['runr_body'] / 2) + 8);
  $stun_damage = ceil(($a_runners['runr_willpower'] / 2) + 8);

  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Condition Monitor (P/S)</th>";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $physical_damage . "/" . $stun_damage . "</td>\n";
  $output .= "</tr>\n";


  $header = "Armor";
  $armor = "";
  $comma = '';
  $q_string  = "select r_arm_id,r_arm_details,arm_name,arm_rating ";
  $q_string .= "from r_armor ";
  $q_string .= "left join armor on armor.arm_id = r_armor.r_arm_number ";
  $q_string .= "where r_arm_character = " . $formVars['id'] . " ";
  $q_string .= "order by arm_name,arm_rating ";
  $q_r_armor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_armor) > 0) {
    while ($a_r_armor = mysqli_fetch_array($q_r_armor)) {

      $arm_name = $a_r_armor['arm_name'];
      if ($a_r_armor['r_arm_details'] != '') {
        $arm_name = $a_r_armor['arm_name'] . " (" . $a_r_armor['r_arm_details'] . ")";
      }

      $rating = return_Rating($a_r_armor['arm_rating']);

      $armor = $comma . $arm_name;
      if ($rating != '--') {
        $armor .= " " . $rating;
      }

      $paren = " (";
      $q_string  = "select acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Clothing and Armor\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_armor['r_arm_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $armor .= $paren . $a_r_accessory['acc_name'];
          if ($rating != '--') {
            $armor .= " " . $rating;
          }
          $paren = ", ";
        }
        if ($paren != " (") {
          $armor .= ")";
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $armor . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  if ($a_runners['ver_version'] == 5.0) {
    $mental_limit   = ceil((($a_runners['runr_logic'] * 2)    + $a_runners['runr_intuition'] + $a_runners['runr_willpower']) /3);
    $physical_limit = ceil((($a_runners['runr_strength'] * 2) + $a_runners['runr_body']      + $a_runners['runr_reaction'])  /3);
    $social_limit   = ceil((($a_runners['runr_charisma'] * 2) + $a_runners['runr_willpower'] + $a_runners['runr_essence'])   /3);

    if ($mental_limit > $social_limit) {
      $astral_limit = $mental_limit;
    } else {
      $astral_limit = $social_limit;
    }

    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Limits</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Physical " . $physical_limit . ", ";
    $output .= "Mental " . $mental_limit . ", ";
    $output .= "Social " . $social_limit;
    if ($a_runners['runr_magic'] > 0) {
      $output .= ", Astral " . $astral_limit . "";
    }
    $output .= "</td>\n";
    $output .= "</tr>\n";
  }


  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Physical Init</th>";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6</td>\n";
  $output .= "</tr>\n";


  $header = "Matrix Init";
  $matrix = '';
  $colon = ": ";
  $q_string  = "select r_link_id,link_brand,link_model,link_data ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_string .= "order by link_rating,link_cost ";
  $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

      $matrix  = $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'];

      $matrix .= $colon . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
      $colon = ", ";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysqli_fetch_array($q_r_accessory);

        $matrix .= $colon . "Cold-sim " . ($a_r_commlink['link_data'] + $a_runners['runr_intuition']) . " + 3d6";
        $colon = ", ";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $matrix .= $colon . "Hot-sim " . ($a_r_commlink['link_data'] + $a_runners['runr_intuition']) . " + 4d6";
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $matrix . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $colon = ": ";
  $q_string  = "select r_cmd_id,cmd_brand,cmd_model,cmd_data ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_string .= "order by cmd_brand,cmd_model,cmd_rating ";
  $q_r_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_command) > 0) {
    while ($a_r_command = mysqli_fetch_array($q_r_command)) {

      $matrix  = $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'];

      $matrix .= $colon . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
      $colon = ", ";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_command['r_cmd_id'] . " ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysqli_fetch_array($q_r_accessory);

        $matrix .= $colon . "Cold-sim " . ($a_r_command['cmd_data'] + $a_runners['runr_intuition']) . " + 3d6";
        $colon = ", ";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $matrix .= $colon . "Hot-sim " . ($a_r_command['cmd_data'] + $a_runners['runr_intuition']) . " + 4d6";
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $matrix . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $colon = ": ";
  $q_string  = "select r_deck_id,deck_brand,deck_model,r_deck_data ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_string .= "order by deck_brand,deck_model,deck_rating ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberdeck) > 0) {
    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $matrix  = $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'];

      $matrix .= $colon . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
      $colon = ", ";

      $q_string  = "select acc_name ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and acc_name like \"Sim Module%\" and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        $a_r_accessory = mysqli_fetch_array($q_r_accessory);

        $matrix .= $colon . "Cold-sim " . ($a_r_cyberdeck['r_deck_data'] + $a_runners['runr_intuition']) . " + 3d6";
        $colon = ", ";

        if ($a_r_accessory['acc_name'] == "Sim Module With Hot-Sim") {
          $matrix .= $colon . "Hot-sim " . ($a_r_cyberdeck['r_deck_data'] + $a_runners['runr_intuition']) . " + 4d6";
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $matrix . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }

# technomancers only have AR and VR Hot-Sim stats.
# use resonance + intuition for stats
  $colon = ": ";
  if ($a_runners['runr_resonance'] > 0) {
    $matrix  = "Persona";

    $matrix .= $colon . "AR " . ($a_runners['runr_reaction'] + $a_runners['runr_intuition']) . " + 1d6";
    $colon = ", ";

    $matrix .= $colon . "Hot-sim " . ($a_runners['runr_resonance'] + $a_runners['runr_intuition']) . " + 4d6";
    $colon = ", ";

    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $matrix . "</td>\n";
    $output .= "</tr>\n";
    $header = "&nbsp;";
  }


  if ($a_runners['runr_magic'] > 0) {
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Astral Init</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . ($a_runners['runr_intuition'] * 2) . " + 2d6</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Active Skills";
  $active = '';
  $comma = '';
  $q_string  = "select act_id,act_name,att_name,att_column,act_default ";
  $q_string .= "from active ";
  $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
  $q_string .= "left join versions on versions.ver_id = active.act_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by act_name ";
  $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_active = mysqli_fetch_array($q_active)) {

    $q_string  = "select r_act_rank,r_act_specialize,r_act_expert ";
    $q_string .= "from r_active ";
    $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " ";
    $q_r_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_r_active) > 0) {
      $a_r_active = mysqli_fetch_array($q_r_active);

      $act_name = $a_active['act_name'] . " ";
      $act_rank = $a_r_active['r_act_rank'];

      $expert = ' + 2';
      if ($a_r_active['r_act_expert']) {
        $expert = "* + 3";
      }

      $r_act_name = '';
      $r_act_rank = '';
      if (strlen($a_r_active['r_act_specialize']) > 0) {
        $r_act_name = " (" . $a_r_active['r_act_specialize'] . $expert . ") ";
      }

      $active .= $comma . $act_name . $act_rank . $r_act_name;
      $comma = ", ";
    }
  }
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $active . "</td>\n";
  $output .= "</tr>\n";


  $header = "Knowledge Skills";
  $knowledge = "";
  $comma = '';
  $q_string  = "select know_name,know_attribute,r_know_rank,r_know_specialize ";
  $q_string .= "from r_knowledge ";
  $q_string .= "left join knowledge on knowledge.know_id = r_knowledge.r_know_number ";
  $q_string .= "where r_know_character = " . $formVars['id'] . " ";
  $q_string .= "order by know_name ";
  $q_r_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_knowledge) > 0) {
    while ($a_r_knowledge = mysqli_fetch_array($q_r_knowledge)) {

      $know_name = $a_r_knowledge['know_name'] . " ";
      $know_rank = $a_r_knowledge['r_know_rank'];

      $r_know_name = '';
      $r_know_rank = '';
      if (strlen($a_r_knowledge['r_know_specialize']) > 0) {
        $r_know_name = " (" . $a_r_knowledge['r_know_specialize'] . ") ";
        $r_know_rank = '(+2)';
      }

      $knowledge .= $comma . $know_name . $r_know_name . $know_rank . $r_know_rank;
      $comma = ', ';

    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $knowledge . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Languages";
  $languages = "";
  $comma = '';
  $q_string  = "select lang_name,lang_attribute,r_lang_rank,r_lang_specialize ";
  $q_string .= "from r_language ";
  $q_string .= "left join language on language.lang_id = r_language.r_lang_number ";
  $q_string .= "where r_lang_character = " . $formVars['id'] . " ";
  $q_string .= "order by lang_name ";
  $q_r_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_language) > 0) {
    while ($a_r_language = mysqli_fetch_array($q_r_language)) {

      $lang_name = $a_r_language['lang_name'];

      if ($a_r_language['r_lang_rank'] == 0) {
        $lang_rank = " N";
      } else {
        $lang_rank = " " . $a_r_language['r_lang_rank'];
      }

      $r_lang_name = '';
      $r_lang_rank = '';
      if (strlen($a_r_language['r_lang_specialize']) > 0) {
        if ($a_r_language['r_lang_rank'] > 0) {
          $r_lang_name = " (" . $a_r_language['r_lang_specialize'] . ")";
          $r_lang_rank = "(+2)";
        }
      }

      $languages .= $comma . $lang_name . $r_lang_name . $lang_rank . $r_lang_rank;
      $comma = ', ';
    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $languages . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Qualities";
  $qualities = "";
  $comma = "";
  $q_string  = "select qual_name,qual_value,qual_desc,r_qual_details ";
  $q_string .= "from r_qualities ";
  $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
  $q_string .= "where r_qual_character = " . $formVars['id'] . " ";
  $q_string .= "order by qual_name ";
  $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_qualities) > 0) {
    while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

      if (strlen($a_r_qualities['r_qual_details']) > 0) {
        $qualities .= $comma . $a_r_qualities['qual_name'] . " (" . $a_r_qualities['r_qual_details'] . ")";
      } else {
        $qualities .= $comma . $a_r_qualities['qual_name'];
      }
      $comma = ", ";
    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $qualities . "</td>\n";
    $output .= "</tr>\n";
  }

  $header = "Adept Powers";
  $adept = "";
  $comma = "";
  $q_string  = "select r_adp_id,r_adp_number,r_adp_level,r_adp_specialize,adp_name,adp_desc,adp_power,adp_level ";
  $q_string .= "from r_adept ";
  $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
  $q_string .= "where r_adp_character = " . $formVars['id'] . " ";
  $q_string .= "order by adp_name ";
  $q_r_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_adept) > 0) {
    while ($a_r_adept = mysqli_fetch_array($q_r_adept)) {

      if (strlen($a_r_adept['r_adp_specialize']) > 0) {
        $adept .= $comma . $a_r_adept['adp_name'] . " (" . $a_r_adept['r_adp_specialize'] . ")";
      } else {
        $adept .= $comma . $a_r_adept['adp_name'];
      }
      if ($a_r_adept['r_adp_level'] > 0) {
        $adept .= " [" . $a_r_adept['r_adp_level'] . "]";
      }
      $comma = ", ";
    }

    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $adept . "</td>\n";
    $output .= "</tr>\n";
  }

  $header = "Complex Forms";
  $complex = "";
  $comma = "";
  $q_string  = "select r_form_id,r_form_number,form_name,form_target,form_duration,form_fading ";
  $q_string .= "from r_complexform ";
  $q_string .= "left join complexform on complexform.form_id = r_complexform.r_form_number ";
  $q_string .= "where r_form_character = " . $formVars['id'] . " ";
  $q_string .= "order by form_name ";
  $q_r_complexform = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_complexform) > 0) {
    while ($a_r_complexform = mysqli_fetch_array($q_r_complexform)) {

      $complex .= $comma . $a_r_complexform['form_name'];
      $comma = ", ";
    }

    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $complex . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Spells";
  $spells = "";
  $comma = "";
  $q_string  = "select r_spell_special,spell_name ";
  $q_string .= "from r_spells ";
  $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
  $q_string .= "where r_spell_character = " . $formVars['id'] . " ";
  $q_string .= "order by spell_name ";
  $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_spells) > 0) {
    while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

      if (strlen($a_r_spells['r_spell_special']) > 0) {
        $special = " (" . $a_r_spells['r_spell_special'] . ")";
      } else {
        $special = '';
      }

      $spells .= $comma . $a_r_spells['spell_name'] . $special;
      $comma = ", ";

    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $spells . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Alchemical Preparations";
  $alchemy = "";
  $comma = "";
  $q_string  = "select r_alc_special,spell_name ";
  $q_string .= "from r_alchemy ";
  $q_string .= "left join spells on spells.spell_id = r_alchemy.r_alc_number ";
  $q_string .= "where r_alc_character = " . $formVars['id'] . " ";
  $q_string .= "order by spell_name ";
  $q_r_alchemy = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_alchemy) > 0) {
    while ($a_r_alchemy = mysqli_fetch_array($q_r_alchemy)) {

      if (strlen($a_r_alchemy['r_alc_special']) > 0) {
        $special = " (" . $a_r_alchemy['r_alc_special'] . ")";
      } else {
        $special = '';
      }

      $alchemy .= $comma . $a_r_alchemy['spell_name'] . $special;
      $comma = ", ";

    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $alchemy . "</td>\n";
    $output .= "</tr>\n";
  }

  $header = "Mentor Spirit";
  $mentor = "";
  $q_string  = "select r_mentor_id,mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage ";
  $q_string .= "from r_mentor ";
  $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
  $q_string .= "where r_mentor_character = " . $formVars['id'] . " ";
  $q_string .= "order by mentor_name ";
  $q_r_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_mentor) > 0) {
    while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

      $mentor = $a_r_mentor['mentor_name'];

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $mentor . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Tradition";
  $tradition = "";
  $q_string  = "select r_trad_id,trad_name,trad_description ";
  $q_string .= "from r_tradition ";
  $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
  $q_string .= "where r_trad_character = " . $formVars['id'] . " ";
  $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_tradition) > 0) {
    while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

      $tradition = $a_r_tradition['trad_name'];

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $tradition . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Commlink";
  $commlink = "";
  $q_string  = "select r_link_id,link_id,link_brand,link_model,link_rating,r_link_access,r_link_active ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_string .= "order by link_rating,link_cost ";
  $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

      $rating = return_Rating($a_r_commlink['link_rating']);

      $commlink = $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'];

      $paren = " (";
      if ($rating != "--") {
        $commlink .= $paren . "Rating " . $rating;
        $paren = ", ";
      }

      $with = " /w";
      $q_string  = "select r_acc_id,acc_id,acc_class,acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Commlinks\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $commlink .= $with . $a_r_accessory['acc_name'];

          $with = " [";
          if ($rating != '--') {
            $commlink .= $with . "Rating " . $rating;
          }
        }
        if ($with != " [") {
          $commlink .= "]";
        }
      }
      if ($paren != " (") {
        $commlink .= ")";
      }

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $commlink . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Rigger Command Console";
  $command = "";
  $q_string  = "select r_cmd_id,cmd_brand,cmd_model,cmd_rating,cmd_programs,";
  $q_string .= "cmd_data,cmd_firewall,r_cmd_noise,r_cmd_sharing,r_cmd_access ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_string .= "order by cmd_brand,cmd_model,cmd_rating ";
  $q_r_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_command) > 0) {
    while ($a_r_command = mysqli_fetch_array($q_r_command)) {

      $rating = return_Rating($a_r_command['cmd_rating']);

      $command = $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'];

      $paren = " (";
      if ($rating != "--") {
        $command .= $paren . "Rating " . $rating;
        $paren = ", ";
      }
      $command .= $paren . "Data Processing " . $a_r_command['cmd_data'];
      $command .= $paren . "Firewall "        . $a_r_command['cmd_firewall'];
      $command .= $paren . "Noise Reduction " . $a_r_command['r_cmd_noise'];
      $command .= $paren . "Sharing "         . $a_r_command['r_cmd_sharing'];
      $command .= $paren . "Programs "        . $a_r_command['cmd_programs'];
      $command .= ")";

# legal programs first
      $program = " (Common: ";
      $q_string  = "select pgm_name ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_command['r_cmd_id'] . " and pgm_type = 2 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {
        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $command .= $program . $a_r_program['pgm_name'];
          $program = ", ";
        }
        $command .= ")";
      }

# now Hacking programs
      $program = " (Hacking: ";
      $q_string  = "select pgm_name ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_command['r_cmd_id'] . " and pgm_type = 3 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {
        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $command .= $program . $a_r_program['pgm_name'];
          $program = ", ";
        }
        $command .= ")";
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $command . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Cyberdeck";
  $cyberdeck = "";
  $q_string  = "select r_deck_id,deck_brand,deck_model,deck_rating,deck_programs,r_deck_attack,r_deck_sleaze,";
  $q_string .= "r_deck_data,r_deck_firewall,r_deck_access ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_string .= "order by deck_brand,deck_model,deck_rating ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberdeck) > 0) {
    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $rating = return_Rating($a_r_cyberdeck['deck_rating']);

      $cyberdeck = $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'];

      $paren = " (";
      if ($rating != "--") {
        $cyberdeck .= $paren . "Rating " . $rating;
        $paren = ", ";
      }
      $cyberdeck .= $paren . "ASDF: " . $a_r_cyberdeck['r_deck_attack'];
      $cyberdeck .= " " . $a_r_cyberdeck['r_deck_sleaze'];
      $cyberdeck .= " " . $a_r_cyberdeck['r_deck_data'];
      $cyberdeck .= " " . $a_r_cyberdeck['r_deck_firewall'];
      $cyberdeck .= $paren . "Programs " . $a_r_cyberdeck['deck_programs'];
      $cyberdeck .= ")";


# accessories
      $bracket = " [";
      $q_string  = "select r_acc_id,acc_id,acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql =" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $acc_rating = return_Rating($a_r_accessory['acc_rating']);

          $cyberdeck .= " [" . $a_r_accessory['acc_name'];
          if ($acc_rating != '--') {
            $cyberdeck .= " (Rating " . $acc_rating . ")";
          }
        }
        $cyberdeck .= "]";
      }


# legal programs first
      $program = " (Common: ";
      $q_string  = "select pgm_name ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 0 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {
        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $cyberdeck .= $program . $a_r_program['pgm_name'];
          $program = ", ";
        }
        $cyberdeck .= ")";
      }

# now Hacking programs
      $program = " (Hacking: ";
      $q_string  = "select pgm_name ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 1 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {
        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $cyberdeck .= $program . $a_r_program['pgm_name'];
          $program = ", ";
        }
        $cyberdeck .= ")";
      }

# now Agents
      $agent = " (Agent: ";
      $q_string  = "select agt_name,agt_rating ";
      $q_string .= "from r_agents ";
      $q_string .= "left join agents on agents.agt_id = r_agents.r_agt_number ";
      $q_string .= "where r_agt_character = " . $formVars['id'] . " and r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_string .= "order by agt_name ";
      $q_r_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_agents) > 0) {
        while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {

          $rating = return_Rating($a_r_agents['agt_rating']);

          $cyberdeck .= $agent . $a_r_agents['agt_name'];
          if ($rating != "--") {
            $cyberdeck .= " (" . $rating . ")";
          }
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $cyberdeck . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Cyberware";
  $cyberware = "";
  $paren = '';
  $q_string  = "select r_ware_id,r_ware_specialize,ware_name,ware_rating,grade_name,grade_essence ";
  $q_string .= "from r_cyberware ";
  $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
  $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
  $q_string .= "where r_ware_character = " . $formVars['id'] . " ";
  $q_string .= "order by ware_name,ware_rating ";
  $q_r_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberware) > 0) {
    while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {

      $ware_name = $a_r_cyberware['ware_name'];
      if ($a_r_cyberware['r_ware_specialize'] != '') {
        $ware_name .= " (" . $a_r_cyberware['r_ware_specialize'] . ")";
      }

      $grade = '';
      if ($a_r_cyberware['grade_essence'] != 1.00) {
        $grade = $a_r_cyberware['grade_name'];
      }

      $rating = return_Rating($a_r_cyberware['ware_rating']);

      $cyberware .= $paren . $ware_name;

      $paren = " [";
      if ($grade != '') {
        $cyberware .= $paren . $grade;
        $paren = ", ";
      }
      if ($rating != '--') {
        $cyberware .= $paren . "Rating " . $rating;
        $paren = ", ";
      }

      $q_string  = "select r_acc_id,acc_id,acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $cyberware .= $paren . $a_r_accessory['acc_name'];
          if ($rating != '--') {
            $cyberware .= " (Rating " . $rating . ")";
          }
          $paren = ", ";
        }
      }
# basically was the paren variable used? If so, add the trailing paren
      if ($paren != " [") {
        $cyberware .= "]";
      }
# now set to comma for the next item if any.
      $paren = ", ";
      
    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $cyberware . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Bioware";
  $bioware = "";
  $paren = "";
  $q_string  = "select r_bio_id,r_bio_specialize,bio_name,bio_rating,grade_name,grade_essence ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
  $q_string .= "where r_bio_character = " . $formVars['id'] . " ";
  $q_string .= "order by bio_class,bio_name,bio_rating ";
  $q_r_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_bioware) > 0) {
    while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

      $bio_name = $a_r_bioware['bio_name'];
      if ($a_r_bioware['r_bio_specialize'] != '') {
        $bio_name = $a_r_bioware['bio_name'] . " (" . $a_r_bioware['r_bio_specialize'] . ")";
      }

      $grade = '';
      if ($a_r_bioware['grade_essence'] != 1.00) {
        $grade = $a_r_bioware['grade_name'];
      }

      $rating = return_Rating($a_r_bioware['bio_rating']);

      $bioware .= $paren . $bio_name;

      $paren = " (";
      if ($grade != '') {
        $bioware .= $paren . $grade;
        $paren = ", ";
      }
      if ($rating != "--") {
        $bioware .= $paren . "Rating " . $rating;
        $paren = ", ";
      }

# basically was the paren variable used? If so, add the trailing paren
      if ($paren != " (") {
        $bioware .= ")";
      }
# now set to comma for the next item if any.
      $paren = ", ";
      
    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $bioware . "</td>\n";
    $output .= "</tr>\n";
  }


  $header = "Vehicles";
  $vehicles = '';
  $q_string  = "select r_veh_id,veh_type,veh_make,veh_model,veh_onacc,veh_offacc,veh_onhand,";
  $q_string .= "veh_offhand,veh_onspeed,veh_offspeed,veh_pilot,veh_body,veh_armor,veh_sensor,";
  $q_string .= "veh_onseats,veh_offseats ";
  $q_string .= "from r_vehicles ";
  $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
  $q_string .= "where r_veh_character = " . $formVars['id'] . " ";
  $q_string .= "order by veh_make,veh_model ";
  $q_r_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_vehicles) > 0) {
    while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {

      $veh_handling = return_Handling($a_r_vehicles['veh_onhand'], $a_r_vehicles['veh_offhand']);

      $veh_speed = return_Speed($a_r_vehicles['veh_onspeed'], $a_r_vehicles['veh_offspeed']);

      $veh_acceleration = return_Acceleration($a_r_vehicles['veh_onacc'], $a_r_vehicles['veh_offacc']);

      $veh_seats = return_Seats($a_r_vehicles['veh_onseats'], $a_r_vehicles['veh_offseats']);

      $vehicles  = $a_r_vehicles['veh_make'] . " " . $a_r_vehicles['veh_model'];
      $vehicles .= " [" . $a_r_vehicles['veh_type'] . ", ";
      $vehicles .= "Handling " . $veh_handling . ", ";
      $vehicles .= "Speed " . $veh_speed . ", ";
      $vehicles .= "Accel " . $veh_acceleration . ", ";
      $vehicles .= "Body " . $a_r_vehicles['veh_body'] . ", ";
      $vehicles .= "Armor " . $a_r_vehicles['veh_armor'] . ", ";
      $vehicles .= "Pilot " . $a_r_vehicles['veh_pilot'] . ", ";
      $vehicles .= "Sensor " . $a_r_vehicles['veh_sensor'];
      if ($veh_seats != '') {
        $vehicles .= ", Seats " . $veh_seats;
      }
      $vehicles .= "]";


      $comma = " w/";
      $q_string  = "select r_acc_id,acc_name,acc_mount ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Vehicles\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
      $q_string .= "order by acc_name ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          if ($a_r_accessory['acc_mount'] != '') {
            $vehicles .= $comma . $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
          } else {
            $vehicles .= $comma . $a_r_accessory['acc_name'];
          }
          $comma = ", ";
        }
      }

      $comma = ", w/";
      $q_string  = "select fa_name ";
      $q_string .= "from r_firearms ";
      $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
      $q_string .= "where r_fa_character = " . $formVars['id'] . " and r_fa_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
      $q_string .= "order by fa_name ";
      $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_firearms) > 0) {
        while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

          $vehicles .= $comma . $a_r_firearms['fa_name'];
        }
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $vehicles . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Gear";
  $gear = "";
  $comma = "";
  $q_string  = "select r_gear_id,gear_name,gear_rating,r_gear_amount,r_gear_details,gear_capacity ";
  $q_string .= "from r_gear ";
  $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
  $q_string .= "left join class on class.class_id = gear.gear_class ";
  $q_string .= "where r_gear_character = " . $formVars['id'] . " and gear_name != \"Fake SIN\" and gear_name != \"Fake License\" and gear_name not like \"%Spell Formula%\" ";
  $q_string .= "order by gear_name,gear_rating ";
  $q_r_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_gear) > 0) {
    while ($a_r_gear = mysqli_fetch_array($q_r_gear)) {

      $r_gear_amount = '';
      if ($a_r_gear['r_gear_amount'] > 1) {
        $r_gear_amount = "(" . $a_r_gear['r_gear_amount'] . "x) ";
      }

      $gear_name = $r_gear_amount . $a_r_gear['gear_name'];
      if ($a_r_gear['r_gear_details'] != '') {
        $gear_name = $a_r_gear['gear_name'] . " (" . $a_r_gear['r_gear_details'] . ")";
      }

      $rating = return_Rating($a_r_gear['gear_rating']);

      $capacity = return_Capacity($a_r_gear['gear_capacity']);

      $gear .= $comma . $gear_name;
      $bracket = " [";
      if ($rating != '--') {
        $gear .= $bracket . "Rating " . $rating;
        $bracket = ", ";
      }

      $comma = " w/";
      $q_string  = "select acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $gear .= $comma . $a_r_accessory['acc_name'];
          if ($a_r_accessory['acc_rating'] > 0) {
            $gear .= " (Rating " . $rating . ")";
          }
          $comma = ", ";
        }
      }
      if ($bracket != " [") {
        $gear .= "]";
      }
      $comma = ", ";
    }
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $gear . "</td>\n";
    $output .= "</tr>\n";
  }



  $header = "Melee Weapons";
  $melee = "&nbsp;";
  $q_string  = "select r_melee_id,class_name,melee_class,melee_name,melee_acc,melee_reach,melee_damage,";
  $q_string .= "melee_type,melee_flag,melee_strength,melee_ap ";
  $q_string .= "from r_melee ";
  $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
  $q_string .= "left join class on class.class_id = melee.melee_class ";
  $q_string .= "where r_melee_character = " . $formVars['id'] . " ";
  $q_string .= "order by melee_class,melee_name ";
  $q_r_melee = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_melee) > 0) {
    while ($a_r_melee = mysqli_fetch_array($q_r_melee)) {

      $melee_reach = '--';
      if ($a_r_melee['melee_reach'] > 0) {
        $melee_reach = $a_r_melee['melee_reach'];
      }

      $melee_damage = "";
      if ($a_r_melee['melee_strength']) {
        $q_string  = "select runr_strength ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        $a_runners = mysqli_fetch_array($q_runners);

        $melee_damage = ($a_runners['runr_strength'] + $a_r_melee['melee_damage']);
      } else {
        if ($a_r_melee['melee_damage'] != 0) {
          $melee_damage = $a_r_melee['melee_damage'];
        }
      }

      if (strlen($a_r_melee['melee_type']) > 0) {
        $melee_damage .= $a_r_melee['melee_type'];
      }
      if (strlen($a_r_melee['melee_flag']) > 0) {
        $melee_damage .= "(" . $a_r_melee['melee_flag'] . ")";
      }

      $melee_ap = '--';
      if ($a_r_melee['melee_ap'] != 0) {
        $melee_ap = $a_r_melee['melee_ap'];
      }

      $melee  = $a_r_melee['melee_name'] . " [";
      $melee .= $a_r_melee['class_name'] . ", ";
      $melee .= "Reach " . $melee_reach . ", ";
      $melee .= "Acc " . $a_r_melee['melee_acc'] . ", ";
      $melee .= "DV " . $melee_damage . ", ";
      $melee .= "AP " . $melee_ap . "]";

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $melee . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Projectile Weapons";
  $projectile = "&nbsp;";
  $q_string  = "select r_proj_id,proj_id,class_name,proj_name,proj_rating,proj_acc,proj_damage,";
  $q_string .= "proj_type,proj_strength,proj_ap ";
  $q_string .= "from r_projectile ";
  $q_string .= "left join projectile on projectile.proj_id = r_projectile.r_proj_number ";
  $q_string .= "left join class on class.class_id = projectile.proj_class ";
  $q_string .= "where r_proj_character = " . $formVars['id'] . " ";
  $q_string .= "order by class_name,proj_name ";
  $q_r_projectile = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_projectile) > 0) {
    while ($a_r_projectile = mysqli_fetch_array($q_r_projectile)) {

      $proj_rating = return_Rating($a_r_projectile['proj_rating']);
      $proj_damage = return_Strength($a_r_projectile['proj_damage'], $a_r_projectile['proj_type'], "", $a_r_projectile['proj_strength']);
      $proj_ap = return_Penetrate($a_r_projectile['proj_ap']);

      $projectile  = $a_r_projectile['proj_name'];
      if ($proj_rating != "--") {
        $projectile .= " (Rating " . $proj_rating . ")";
      }
      $projectile .= " [" . $a_r_projectile['class_name'] . ", ";
      $projectile .= "Acc " . $a_r_projectile['proj_acc'] . ", ";
      $projectile .= "DV " . $proj_damage . ", ";
      $projectile .= "AP " . $proj_ap . "]";

      $with = " w/";
      $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_rating,ammo_ap ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_projectile['r_proj_id'] . " ";
      $q_string .= "order by ammo_name,class_name ";
      $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

          $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);
          $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

          $projectile .= $with . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'];
          $with = " ";
          if ($a_r_ammo['ammo_mod'] != '') {
            $projectile .= $with . "DV " . $a_r_ammo['ammo_mod'];
            $with = ", ";
          }
          if ($ammo_ap != '--') {
            $projectile .= $with . $ammo_ap;
          }
          if ($ammo_rating != '--') {
            $projectile .= " (Rating " . $ammo_rating . ")";
          }
          $with = ", ";

        }
      }

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $projectile . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Firearms";
  $firearm = "&nbsp;";
  $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
  $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
  $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "left join class on class.class_id = firearms.fa_class ";
  $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
  $q_string .= "where r_fa_character = " . $formVars['id'] . " and r_fa_faid = 0 ";
  $q_string .= "order by fa_name,fa_class ";
  $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_firearms) > 0) {
    while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

      $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

      $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

      $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

      $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

      $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

      $firearm  = $a_r_firearms['fa_name'] . " [";
      $firearm .= $a_r_firearms['class_name'] . ", ";
      $firearm .= "Acc " . $a_r_firearms['fa_acc'] . ", ";
      $firearm .= "DV " . $fa_damage . ", ";
      $firearm .= "AP " . $fa_ap . ", ";
      $firearm .= $fa_mode . ", ";
      $firearm .= "RC " . $fa_rc . ", ";
      $firearm .= $fa_ammo . "]";

      $with = " w/";
# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,";
      $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $acc_name = $a_r_accessory['acc_name'];
          if ($a_r_accessory['acc_mount'] != '') {
            $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
          }

          $firearm .= $with . $acc_name;
          $with = ', ';

        }
      }

      $with = " w/";
# associate any ammo with the weapon
      $q_string  = "select r_ammo_rounds,ammo_name,ammo_rounds,ammo_mod,ammo_ap,ammo_blast ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
      $q_string .= "order by ammo_name ";
      $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

          $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

          $firearm .= $with . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . " " . $a_r_ammo['class_name'];
          $bracket = " [";
          if ($a_r_ammo['ammo_mod'] != '') {
            $firearm .= $bracket . "DV " . $a_r_ammo['ammo_mod'];
            $bracket = ', ';
          }
          if ($ammo_ap != '--') {
            $firearm .= $bracket . "AP " . $ammo_ap;
            $bracket = ', ';
          }
          if ($a_r_ammo['ammo_blast'] != '') {
            $firearm .= $bracket . "Blast " . $a_r_ammo['ammo_blast'];
            $bracket = ', ';
          }
          if ($bracket != " [") {
            $firearm .= "]";
          }
          $with = ", ";

        }
      }

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $firearm . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Ammunition";
  $ammo = "";
  $comma = ", ";
  $q_string  = "select r_ammo_rounds,ammo_rounds,class_name,ammo_name,ammo_mod,ammo_ap,ammo_blast ";
  $q_string .= "from r_ammo ";
  $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
  $q_string .= "left join class on class.class_id = ammo.ammo_class ";
  $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = 0 ";
  $q_string .= "order by class_name,ammo_name ";
  $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_ammo) > 0) {
    while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

      $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

      $ammo  = $a_r_ammo['ammo_name'];
      $ammo .= " (" . $a_r_ammo['r_ammo_rounds'] . ")";
      $ammo .= " [" . $a_r_ammo['class_name'];
      if ($a_r_ammo['ammo_mod'] != '') {
        $ammo .= $comma . "DV " . $a_r_ammo['ammo_mod'];
        $comma = ", ";
      }
      if ($ammo_ap != '--') {
        $ammo .= $comma . "AP " . $ammo_ap;
        $comma = ", ";
      }
      if ($a_r_ammo['ammo_blast'] != '') {
        $ammo .= $comma . "Blast " . $a_r_ammo['ammo_blast'];
        $comma = ", ";
      }
      $ammo .= "]";

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $ammo . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Identity/Licenses";
  $identity = "";
  $comma = "";
  $q_string  = "select id_id,id_name,id_type,id_rating,id_background ";
  $q_string .= "from r_identity ";
  $q_string .= "where id_character = " . $formVars['id'] . " ";
  $q_string .= "order by id_name ";
  $q_r_identity = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_identity) > 0) {
    while ($a_r_identity = mysqli_fetch_array($q_r_identity)) {

      if ($a_r_identity['id_type'] == 2) {
        $identity = "Criminal SIN ";
      } else {
        if ($a_r_identity['id_type'] == 1) {
          $identity = "Fake SIN ";
        } else {
          $identity = "SIN ";
        }
      }

      $identity .= " (" . $a_r_identity['id_name'] . ")";
      $identity .= " (Rating " . $a_r_identity['id_rating'] . ")";

      $comma = " [";
      $q_string  = "select lic_id,lic_type,lic_rating ";
      $q_string .= "from r_license ";
      $q_string .= "where lic_identity = " . $a_r_identity['id_id'] . " ";
      $q_string .= "order by lic_type ";
      $q_r_license = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_license) > 0) {
        while ($a_r_license = mysqli_fetch_array($q_r_license)) {

          $identity .= $comma . $a_r_license['lic_type'];
          $identity .= " (Rating " . $a_r_license['lic_rating'] . ")";
          $comma = ", ";
        }
      }
      if ($comma != ' [') {
        $identity .= "]";
      }
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $identity . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Lifestyles";
  $lifestyle = "";
  $q_string  = "select life_style,r_life_desc,r_life_months ";
  $q_string .= "from r_lifestyle ";
  $q_string .= "left join lifestyle on lifestyle.life_id = r_lifestyle.r_life_number ";
  $q_string .= "where r_life_character = " . $formVars['id'] . " ";
  $q_string .= "order by life_style ";
  $q_r_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_lifestyle) > 0) {
    while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {

      $lifestyle  = $a_r_lifestyle['life_style'] . " Lifestyle";
      if ($a_r_lifestyle['r_life_desc'] != '') {
        $lifestyle .= " (" . $a_r_lifestyle['r_life_desc'] . ")";
      }
      $lifestyle .= " [";
      $lifestyle .= $a_r_lifestyle['r_life_months'] . " Months Prepaid";
      $lifestyle .= "]";

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $lifestyle . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Contacts";
  $contact = "";
  $q_string  = "select con_name,con_archetype,r_con_loyalty,r_con_connection ";
  $q_string .= "from r_contact ";
  $q_string .= "left join contact on contact.con_id = r_contact.r_con_number ";
  $q_string .= "where r_con_character = " . $formVars['id'] . " ";
  $q_string .= "order by con_archetype,con_name ";
  $q_r_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_contact) > 0) {
    while ($a_r_contact = mysqli_fetch_array($q_r_contact)) {
      $contact  = $a_r_contact['con_archetype'];
      $contact .= " (" . $a_r_contact['con_name'] . ") ";
      $contact .= " [Connection: " . $a_r_contact['r_con_connection'];
      $contact .= "/Loyalty: " . $a_r_contact['r_con_loyalty'] . "]";

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $contact . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Notes";
  $notes = "";
  $q_string  = "select meta_notes ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "where runr_id = " . $formVars['id'] . " and meta_notes != \"\" ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_runners) > 0) {
    while ($a_runners = mysqli_fetch_array($q_runners)) {

      $notes = $a_runners['meta_notes'];

      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">" . $header . "</th>";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">" . $notes . "</td>\n";
      $output .= "</tr>\n";
      $header = "&nbsp;";
    }
  }


  $header = "Spirits";
  $header = "Sprites";


  $output .= "<tr>\n";


  $output .= "</tr>\n";
  $output .= "</table>\n";

  print "document.getElementById('pregen_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
