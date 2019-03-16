<?php
# Script: projectile.fill.php
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
    $package = "projectile.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_projectile");

      $q_string  = "select class_name,proj_name,proj_rating,proj_acc,proj_damage,proj_type,proj_ap ";
      $q_string .= "from r_projectile ";
      $q_string .= "left join projectile on projectile.proj_id = r_projectile.r_proj_number ";
      $q_string .= "left join class on class.class_id = projectile.proj_class ";
      $q_string .= "where r_proj_id = " . $formVars['id'];
      $q_r_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_projectile = mysql_fetch_array($q_r_projectile);
      mysql_free_result($q_r_projectile);

      $proj_damage = return_Damage($a_r_projectile['proj_damage'], $a_r_projectile['proj_type'], "");

      $proj_ap = return_Penetrate($a_r_projectile['proj_ap']);

      $projectile = " (Rating " . $a_r_projectile['proj_rating'] . ") [" . $a_r_projectile['class_name'] . ", Acc " . $a_r_projectile['proj_acc'] . ", DV " . $proj_damage . ", AP " . $proj_ap . "]";

      print "document.getElementById('r_proj_item').innerHTML = '" . mysql_real_escape_string($a_r_projectile['proj_name'])      . $projectile . "';\n\n";
      print "document.edit.r_proj_number.value = '"                . mysql_real_escape_string($a_r_projectile['r_proj_number']) . "';\n\n";

      print "document.edit.r_proj_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_proj_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
