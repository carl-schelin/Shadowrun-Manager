<?php
# Script: melee.fill.php
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
    $package = "melee.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_melee");

      $q_string  = "select class_name,melee_name,melee_acc,melee_reach,melee_damage,melee_type,melee_flag,melee_ap,";
      $q_string .= "r_melee_number ";
      $q_string .= "from r_melee ";
      $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
      $q_string .= "left join class on class.class_id = melee.melee_class ";
      $q_string .= "where r_melee_id = " . $formVars['id'];
      $q_r_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_melee = mysql_fetch_array($q_r_melee);
      mysql_free_result($q_r_melee);

      $melee_damage = return_Damage($a_r_melee['melee_damage'], $a_r_melee['melee_type'], $a_r_melee['melee_flag']);

      $melee_reach = return_Reach($a_r_melee['melee_reach']);

      $melee_ap = return_Penetrate($a_r_melee['melee_ap']);

      $melee = " [" . $a_r_melee['class_name'] . ", Acc " . $a_r_melee['melee_acc'] . ", Reach " . $melee_reach . ", DV " . $melee_damage . ", AP " . $melee_ap . "]";

      print "document.getElementById('r_melee_item').innerHTML = '" . mysql_real_escape_string($a_r_melee['melee_name'])      . $melee . "';\n\n";
      print "document.edit.r_melee_number.value = '"                . mysql_real_escape_string($a_r_melee['r_melee_number']) . "';\n\n";

      print "document.edit.r_melee_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_melee_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
