<?php
# Script: costs.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "costs.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select runr_body,runr_agility,runr_reaction,runr_strength,runr_willpower,runr_logic,runr_intuition,runr_charisma,runr_magic,runr_initiate,runr_resonance,meta_name ";
  $q_string .= "from metatypes ";
  $q_string .= "left join runners on runners.runr_metatype = metatypes.meta_id ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_metatypes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_metatypes = mysqli_fetch_array($q_metatypes);


  $attributes = 0;
  if ($a_metatypes['meta_name'] == "Human") {
    $attributes += ($a_metatypes['runr_body']      - 1);
    $attributes += ($a_metatypes['runr_agility']   - 1);
    $attributes += ($a_metatypes['runr_reaction']  - 1);
    $attributes += ($a_metatypes['runr_strength']  - 1);
    $attributes += ($a_metatypes['runr_willpower'] - 1);
    $attributes += ($a_metatypes['runr_logic']     - 1);
    $attributes += ($a_metatypes['runr_intuition'] - 1);
    $attributes += ($a_metatypes['runr_charisma']  - 1);
  }
  if ($a_metatypes['meta_name'] == "Elf") {
    $attributes += ($a_metatypes['runr_body']      - 1);
    $attributes += ($a_metatypes['runr_agility']   - 2);
    $attributes += ($a_metatypes['runr_reaction']  - 1);
    $attributes += ($a_metatypes['runr_strength']  - 1);
    $attributes += ($a_metatypes['runr_willpower'] - 1);
    $attributes += ($a_metatypes['runr_logic']     - 1);
    $attributes += ($a_metatypes['runr_intuition'] - 1);
    $attributes += ($a_metatypes['runr_charisma']  - 3);
  }
  if ($a_metatypes['meta_name'] == "Dwarf") {
    $attributes += ($a_metatypes['runr_body']      - 3);
    $attributes += ($a_metatypes['runr_agility']   - 1);
    $attributes += ($a_metatypes['runr_reaction']  - 1);
    $attributes += ($a_metatypes['runr_strength']  - 3);
    $attributes += ($a_metatypes['runr_willpower'] - 2);
    $attributes += ($a_metatypes['runr_logic']     - 1);
    $attributes += ($a_metatypes['runr_intuition'] - 1);
    $attributes += ($a_metatypes['runr_charisma']  - 1);
  }
  if ($a_metatypes['meta_name'] == "Ork") {
    $attributes += ($a_metatypes['runr_body']      - 4);
    $attributes += ($a_metatypes['runr_agility']   - 1);
    $attributes += ($a_metatypes['runr_reaction']  - 1);
    $attributes += ($a_metatypes['runr_strength']  - 3);
    $attributes += ($a_metatypes['runr_willpower'] - 1);
    $attributes += ($a_metatypes['runr_logic']     - 1);
    $attributes += ($a_metatypes['runr_intuition'] - 1);
    $attributes += ($a_metatypes['runr_charisma']  - 1);
  }
  if ($a_metatypes['meta_name'] == "Troll") {
    $attributes += ($a_metatypes['runr_body']      - 5);
    $attributes += ($a_metatypes['runr_agility']   - 1);
    $attributes += ($a_metatypes['runr_reaction']  - 1);
    $attributes += ($a_metatypes['runr_strength']  - 5);
    $attributes += ($a_metatypes['runr_willpower'] - 1);
    $attributes += ($a_metatypes['runr_logic']     - 1);
    $attributes += ($a_metatypes['runr_intuition'] - 1);
    $attributes += ($a_metatypes['runr_charisma']  - 1);
  }

  $skills = 0;
  $groupskills = 0;
  $q_string  = "select act_id,act_group,act_name,att_name,att_column,act_default ";
  $q_string .= "from active ";
  $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
  $q_string .= "left join versions on versions.ver_id = active.act_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by act_name ";
  $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_active = mysqli_fetch_array($q_active)) {

    $q_string  = "select r_act_rank,r_act_specialize ";
    $q_string .= "from r_active ";
    $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " and r_act_group = 0 ";
    $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_active) > 0) {
      $a_r_active = mysqli_fetch_array($q_r_active);

      $skills += $a_r_active['r_act_rank'];

    }
    $q_string  = "select r_act_rank,r_act_specialize ";
    $q_string .= "from r_active ";
    $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " and r_act_group = 1 ";
    $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_active) > 0) {
      $a_r_active = mysqli_fetch_array($q_r_active);


      if ($a_active['act_group'] != '') {
        $groupname[$a_active['act_group']] = 1;
      }

        $grouplist .= $a_active['act_group'];
      if ($groupname[$a_active['act_group']] != 1) {

        $groupskills += $a_r_active['r_act_rank'];
      }

    }
  }

# A bit harder. This one goes through all the r_ tables and calculates the total cost in essence and value for all the gear.

  $totalcosts = 0;
  $totalessence = 0;
  $totalpower = 0;

# only update if an item was added
  $accessory = '';
  $program = '';
  $agent = '';

# r_active - no essence or costs
  $active = '';

# r_adept - r_adp_level for power levels and adp_power, adp_level
  $adept = '';
  $q_string  = "select adp_power,adp_level,r_adp_level ";
  $q_string .= "from r_adept ";
  $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
  $q_string .= "where r_adp_character = " . $formVars['id'] . " ";
  $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_adept = mysqli_fetch_array($q_r_adept)) {
    if ($a_r_adept['adp_level'] == 0) {
      $powerpoints = ($a_r_adept['adp_power'] * $a_r_adept['r_adp_level']);
    } else {
      $powerpoints = $a_r_adept['adp_power'];
    }
    $totalpower += $powerpoints;
    $adept = '03';
  }

# r_alchemy - no essence or costs
  $alchemy = '';

# r_ammo - r_ammo_rounds, ammo_rounds, ammo_cost. ammo_rounds is number per item (10 for ammo, 1 for missile and rocket) and r_ammo_rounds is number of items.
  $ammo = '';
  $q_string  = "select r_ammo_rounds,ammo_cost ";
  $q_string .= "from r_ammo ";
  $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
  $q_string .= "where r_ammo_character = " . $formVars['id'] . " ";
  $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {
    $totalcosts   += ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_cost']);
    $ammo = '05';
  }


# r_armor - arm_cost
  $armor = '';
  $q_string  = "select r_arm_id,arm_cost ";
  $q_string .= "from r_armor ";
  $q_string .= "left join armor on armor.arm_id = r_armor.r_arm_number ";
  $q_string .= "where r_arm_character = " . $formVars['id'] . " ";
  $q_r_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_armor = mysqli_fetch_array($q_r_armor)) {
    $totalcosts   += $a_r_armor['arm_cost'];
    $armor = '06';

# r_accessory - has acc_cost for armor
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Clothing and Armor\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_armor['r_arm_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_bioware - r_bio_grade plus bio_essence and bio_cost
# plus any accessory with bioware also gets modified by grade.
  $bioware = '';
  $bio_essence = 0.00;
  $q_string  = "select grade_essence,grade_cost,bio_essence,bio_cost ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
  $q_string .= "where r_bio_character = " . $formVars['id'] . " ";
  $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {
    $totalessence += ($a_r_bioware['grade_essence'] * $a_r_bioware['bio_essence']);
    $bio_essence += ($a_r_bioware['grade_essence'] * $a_r_bioware['bio_essence']);
    $totalcosts   += ($a_r_bioware['grade_cost'] * $a_r_bioware['bio_cost']);
    $bioware = '07';
  }


# r_commlink - link_cost
  $commlink = '';
  $q_string  = "select r_link_id,link_cost ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {
    $totalcosts   += $a_r_commlink['link_cost'];
    $commlink = '08';

# r_accessory - has acc_cost for armor
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Commlinks\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_command - cmd_cost
  $command = '';
  $q_string  = "select r_cmd_id,cmd_cost ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_command = mysqli_fetch_array($q_r_command)) {
    $totalcosts   += $a_r_command['cmd_cost'];
    $command = '26';

# r_accessory - has acc_cost for armor
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Cyberdecks\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_command['r_cmd_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }

# r_program - has pgm_cost for programs
    $q_string  = "select pgm_cost ";
    $q_string .= "from r_program ";
    $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
    $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_command = " . $a_r_command['r_cmd_id'] . " ";
    $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_program = mysqli_fetch_array($q_r_program)) {
      $totalcosts   += $a_r_program['pgm_cost'];
      $program = '20';
    }
  }


# r_complexform - no essence or costs
  $complexform = '';
# r_contact - no essence or costs
  $contact = '';


# r_cyberdeck - deck_cost
  $cyberdeck = '';
  $q_string  = "select r_deck_id,deck_cost ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_r_cyberdeck = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {
    $totalcosts   += $a_r_cyberdeck['deck_cost'];
    $cyberdeck = '10';

# r_accessory - has acc_cost for cyberdecks
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Cyberdecks\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }

# r_program - has pgm_cost for programs
    $q_string  = "select pgm_cost ";
    $q_string .= "from r_program ";
    $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
    $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
    $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_program = mysqli_fetch_array($q_r_program)) {
      $totalcosts   += $a_r_program['pgm_cost'];
      $program = '20';
    }

# r_agents - agt_cost
    $agents = '';
    $q_string  = "select agt_cost ";
    $q_string .= "from r_agents ";
    $q_string .= "left join agents on agents.agt_id = r_agents.r_agt_number ";
    $q_string .= "where r_agt_character = " . $formVars['id'] . " and r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
    $q_r_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {
      $totalcosts   += $a_r_agents['agt_cost'];
      $agents = '04';
    }
  }


# r_cyberware - r_ware_grade, ware_cost, ware_essence
# plus any accessory with cyberware also gets modified by grade.
  $cyberware = '';
  $q_string  = "select r_ware_id,grade_essence,grade_cost,ware_essence,ware_cost ";
  $q_string .= "from r_cyberware ";
  $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
  $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
  $q_string .= "where r_ware_character = " . $formVars['id'] . " ";
  $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {
    $totalessence += ($a_r_cyberware['grade_essence'] * $a_r_cyberware['ware_essence']);
    $totalcosts   += ($a_r_cyberware['grade_cost'] * $a_r_cyberware['ware_cost']);
    $cyberware = '11';

# r_accessory - has acc_essence and acc_cost
    $q_string  = "select acc_essence,acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalessence += ($a_r_accessory['acc_essence'] * $a_r_cyberware['grade_essence']);
      $totalcosts   += ($a_r_accessory['acc_cost'] * $a_r_cyberware['grade_cost']);
      $accessory = '01';
    }

  }


# r_firearms - fa_cost
  $firearms = '';
  $q_string  = "select r_fa_id,fa_cost ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "where r_fa_character = " . $formVars['id'] . " ";
  $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {
    $totalcosts   += $a_r_firearms['fa_cost'];
    $firearms = '12';

# r_accessory - has acc_cost for firearms
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_gear - gear_cost
  $gear = '';
  $q_string  = "select r_gear_id,gear_cost ";
  $q_string .= "from r_gear ";
  $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
  $q_string .= "where r_gear_character = " . $formVars['id'] . " ";
  $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_gear = mysqli_fetch_array($q_r_gear)) {
    $totalcosts   += $a_r_gear['gear_cost'];
    $gear = '13';

# r_accessory - has acc_cost for firearms
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_identity - no essence or costs
  $identity = '';
# r_knowledge - no essence or costs
  $knowledge = '';
# r_language - no essence or costs
  $language = '';
# r_license - no essence or costs
  $license = '';

# r_lifestyle - life_cost and r_life_months
  $lifestyle = '';
  $q_string  = "select r_life_months,life_mincost,life_maxcost ";
  $q_string  = "select r_life_months ";
  $q_string .= "from r_lifestyle ";
  $q_string .= "left join lifestyle on lifestyle.life_id = r_lifestyle.r_life_number ";
  $q_string .= "where r_life_character = " . $formVars['id'] . " ";
  $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {

# if troll, * 2;
# if dwarf, * 1.2;
    $multiplier = 1;
    if ($a_metatypes['meta_name'] == 'Dwarf') {
      $multiplier = 1.2;
    }
    if ($a_metatypes['meta_name'] == 'Troll') {
      $multiplier = 2;
    }

    $totalcosts   += ($a_r_lifestyle['r_life_months'] * $a_r_lifestyle['life_mincost'] * $multiplier);
    $lifestyle = '18';
  }


# r_melee - melee_cost
  $melee = '';
  $q_string  = "select r_melee_id,melee_cost ";
  $q_string .= "from r_melee ";
  $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
  $q_string .= "where r_melee_character = " . $formVars['id'] . " ";
  $q_r_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_melee = mysqli_fetch_array($q_r_melee)) {
    $totalcosts   += $a_r_melee['melee_cost'];
    $melee = '19';

# r_accessory - has acc_cost for firearms
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Melee\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_melee['r_melee_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_mentor - no essence or costs
  $mentor = '';


# r_projectile - proj_cost
  $projectile = '';
  $q_string  = "select r_proj_id,proj_cost ";
  $q_string .= "from r_projectile ";
  $q_string .= "left join projectile on projectile.proj_id = r_projectile.r_proj_number ";
  $q_string .= "where r_proj_character = " . $formVars['id'] . " ";
  $q_r_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_projectile = mysqli_fetch_array($q_r_projectile)) {
    $totalcosts   += $a_r_projectile['proj_cost'];
    $projectile = '26';

# r_accessory - has acc_cost for projectiles
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Projectile\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_projectile['r_proj_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }


# r_qualities - no essence or costs
  $qualities = '';
# r_spells - no essence or costs (however there is a cost in the gear section)
  $spells = '';
# r_spirit - no essence or costs
  $spirit = '';
# r_sprite - no essence or costs
  $sprite = '';
# r_tradition - no essence or costs
  $tradition = '';

# r_vehicles - veh_cost
  $vehicles = '';
  $q_string  = "select r_veh_id,veh_cost ";
  $q_string .= "from r_vehicles ";
  $q_string .= "left join vehicles on vehicles.veh_id = r_vehicles.r_veh_number ";
  $q_string .= "where r_veh_character = " . $formVars['id'] . " ";
  $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_r_vehicles = mysqli_fetch_array($q_r_vehicles)) {
    $totalcosts   += $a_r_vehicles['veh_cost'];
    $vehicles = '25';

# r_accessory - has acc_cost for vehicles
    $q_string  = "select acc_cost ";
    $q_string .= "from r_accessory ";
    $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
    $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
    $q_string .= "where sub_name = \"Vehicles\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_vehicles['r_veh_id'] . " ";
    $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
      $totalcosts   += $a_r_accessory['acc_cost'];
      $accessory = '01';
    }
  }

# now display the output

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Calculated Power, Essence, and Costs</th>";
  $output .= "</tr>\n";

  $nuyen = '&yen;';

  $output .= "<tr>";
  $output .= "<td class=\"ui-widget-content delete\">Total Power: "   . $totalpower                                      . "</td>";
  $output .= "<td class=\"ui-widget-content delete\">Total Essence: " . $totalessence . "(" . number_format((6.00 - $totalessence), 2, ".", ",") . ")"  . "</td>";
  $output .= "<td class=\"ui-widget-content delete\">Total Costs: "   . number_format($totalcosts, 0, '.', ',') . $nuyen . "</td>";
  $output .= "<td class=\"ui-widget-content delete\">";
  $output .= $accessory . $adept . $agents . $ammo . $armor . $bioware . $commlink;
  $output .= $cyberdeck . $cyberware . $firearms . $gear . $lifestyle . $melee;
  $output .= $program . $vehicles . $command . $projectile;
  $output .= "</td>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .= "<td class=\"ui-widget-content delete\">Metatype: "   . $a_metatypes['meta_name'] . "</td>";
  $output .= "<td class=\"ui-widget-content delete\">Attributes: " . $attributes . "</td>";
  if ($a_metatypes['runr_magic'] > 0) {
    $initiate = '';
    if ($a_metatypes['runr_initiate'] > 0) {
      $initiate = " (" . ($a_metatypes['runr_magic'] + $a_metatypes['runr_initiate']) . ")";
    }
    $output .= "<td class=\"ui-widget-content delete\">Magic: " . $a_metatypes['runr_magic'] . $initiate . "</td>";
  }
  if ($a_metatypes['runr_resonance'] > 0) {
    $output .= "<td class=\"ui-widget-content delete\">Resonance: " . $a_metatypes['runr_resonance'] . "</td>";
  }
  $output .= "<td class=\"ui-widget-content delete\" colspan=\"2\">Skills: "     . $skills . "/" . $groupskills . " (" . $grouplist . ")</td>";
  $output .= "</tr>";
  $output .= "</table>";

  print "document.getElementById('costs_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
