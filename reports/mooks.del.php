<?php
# Script: mooks.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Delete association entries

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "mooks.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_accessory");

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_active");

      $q_string  = "delete ";
      $q_string .= "from r_active ";
      $q_string .= "where r_act_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_adept");

      $q_string  = "delete ";
      $q_string .= "from r_adept ";
      $q_string .= "where r_adp_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_agents");

      $q_string  = "delete ";
      $q_string .= "from r_agents ";
      $q_string .= "where r_agt_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_alchemy");

      $q_string  = "delete ";
      $q_string .= "from r_alchemy ";
      $q_string .= "where r_alc_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_ammo");

      $q_string  = "delete ";
      $q_string .= "from r_ammo ";
      $q_string .= "where r_ammo_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_armor");

      $q_string  = "delete ";
      $q_string .= "from r_armor ";
      $q_string .= "where r_arm_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_bioware");

      $q_string  = "delete ";
      $q_string .= "from r_bioware ";
      $q_string .= "where r_bio_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_command");

      $q_string  = "delete ";
      $q_string .= "from r_command ";
      $q_string .= "where r_cmd_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_commlink");

      $q_string  = "delete ";
      $q_string .= "from r_commlink ";
      $q_string .= "where r_link_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_complexform");

      $q_string  = "delete ";
      $q_string .= "from r_complexform ";
      $q_string .= "where r_form_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_contact");

      $q_string  = "delete ";
      $q_string .= "from r_contact ";
      $q_string .= "where r_con_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_cyberdeck");

      $q_string  = "delete ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "where r_deck_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_cyberware");

      $q_string  = "delete ";
      $q_string .= "from r_cyberware ";
      $q_string .= "where r_ware_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_firearms");

      $q_string  = "delete ";
      $q_string .= "from r_firearms ";
      $q_string .= "where r_fa_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_gear");

      $q_string  = "delete ";
      $q_string .= "from r_gear ";
      $q_string .= "where r_gear_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_identity");

      $q_string  = "delete ";
      $q_string .= "from r_identity ";
      $q_string .= "where id_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_knowledge");

      $q_string  = "delete ";
      $q_string .= "from r_knowledge ";
      $q_string .= "where r_know_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_language");

      $q_string  = "delete ";
      $q_string .= "from r_language ";
      $q_string .= "where r_lang_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_license");

      $q_string  = "delete ";
      $q_string .= "from r_license ";
      $q_string .= "where lic_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_lifestyle");

      $q_string  = "delete ";
      $q_string .= "from r_lifestyle ";
      $q_string .= "where r_life_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_melee");

      $q_string  = "delete ";
      $q_string .= "from r_melee ";
      $q_string .= "where r_melee_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_mentor");

      $q_string  = "delete ";
      $q_string .= "from r_mentor ";
      $q_string .= "where r_mentor_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_program");

      $q_string  = "delete ";
      $q_string .= "from r_program ";
      $q_string .= "where r_pgm_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_projectile");

      $q_string  = "delete ";
      $q_string .= "from r_projectile ";
      $q_string .= "where r_proj_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_qualities");

      $q_string  = "delete ";
      $q_string .= "from r_qualities ";
      $q_string .= "where r_qual_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_spells");

      $q_string  = "delete ";
      $q_string .= "from r_spells ";
      $q_string .= "where r_spell_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_spirit");

      $q_string  = "delete ";
      $q_string .= "from r_spirit ";
      $q_string .= "where r_spirit_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_sprite");

      $q_string  = "delete ";
      $q_string .= "from r_sprite ";
      $q_string .= "where r_sprite_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_tradition");

      $q_string  = "delete ";
      $q_string .= "from r_tradition ";
      $q_string .= "where r_trad_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_vehicles");

      $q_string  = "delete ";
      $q_string .= "from r_vehicles ";
      $q_string .= "where r_veh_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from runners");

      $q_string  = "delete ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from members");

      $q_string  = "delete ";
      $q_string .= "from members ";
      $q_string .= "where mem_runner= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from history");

      $q_string  = "delete ";
      $q_string .= "from history ";
      $q_string .= "where his_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from karma");

      $q_string  = "delete ";
      $q_string .= "from karma ";
      $q_string .= "where kar_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from street");

      $q_string  = "delete ";
      $q_string .= "from street ";
      $q_string .= "where st_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from notoriety");

      $q_string  = "delete ";
      $q_string .= "from notoriety ";
      $q_string .= "where not_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from publicity");

      $q_string  = "delete ";
      $q_string .= "from publicity ";
      $q_string .= "where pub_character= " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Character deleted.');\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }

?>
