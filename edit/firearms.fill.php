<?php
# Script: firearms.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "firearms.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_firearms");

      $q_string  = "select class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,fa_ap,fa_mode1,";
      $q_string .= "fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,r_fa_number,";
      $q_string .= "fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5 ";
      $q_string .= "from r_firearms ";
      $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
      $q_string .= "left join class on class.class_id = firearms.fa_class ";
      $q_string .= "where r_fa_id = " . $formVars['id'];
      $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_firearms = mysqli_fetch_array($q_r_firearms);
      mysqli_free_result($q_r_firearms);

      $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

      $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

      $fa_attack = return_Attack($a_r_firearms['fa_ar1'], $a_r_firearms['fa_ar2'], $a_r_firearms['fa_ar3'], $a_r_firearms['fa_ar4'], $a_r_firearms['fa_ar5']);

      $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

      $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

      $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

      $firearm = " [" . $a_r_firearms['class_name'] . ", Acc " . $a_r_firearms['fa_acc'] . ", DV " . $fa_damage . ", AP " . $fa_ap . ", " . $fa_mode . ", AR " . $fa_attack . ", RC " . $fa_rc . ", " . $fa_ammo . "]";

      print "document.getElementById('r_fa_item').innerHTML = '" . mysqli_real_escape_string($db, $a_r_firearms['fa_name'])      . $firearm . "';\n\n";
      print "document.edit.r_fa_number.value = '"                . mysqli_real_escape_string($db, $a_r_firearms['r_fa_number']) . "';\n\n";

      print "document.edit.r_fa_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_fa_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
