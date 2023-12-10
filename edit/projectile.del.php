<?php
# Script: projectile.del.php
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
    $package = "projectile.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_projectile");

      $q_string  = "select r_proj_character ";
      $q_string .= "from r_projectile ";
      $q_string .= "where r_proj_id = " . $formVars['id'] . " ";
      $q_r_projectile = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_projectile = mysqli_fetch_array($q_r_projectile);

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character = " . $a_r_projectile['r_proj_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

# need to zero out parent ids for ammo.
      $q_string  = "update r_ammo ";
      $q_string .= "set r_ammo_parentid = 0 ";
      $q_string .= "where r_ammo_character = " . $a_r_projectile['r_proj_character'] . " and r_ammo_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      $q_string  = "delete ";
      $q_string .= "from r_projectile ";
      $q_string .= "where r_proj_id= " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Projectile Weapon deleted.');\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
