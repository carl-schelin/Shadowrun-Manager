<?php
# Script: sprite.fill.php
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
    $package = "sprite.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_sprite");

      $q_string  = "select sprite_name,r_sprite_number,r_sprite_level,r_sprite_tasks,r_sprite_registered ";
      $q_string .= "from r_sprite ";
      $q_string .= "left join sprites on sprites.sprite_id = r_sprite.r_sprite_number ";
      $q_string .= "where r_sprite_id = " . $formVars['id'];
      $q_r_sprite = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_sprite = mysqli_fetch_array($q_r_sprite);
      mysql_free_result($q_r_sprite);

      print "document.getElementById('r_sprite_item').innerHTML = '" . mysql_real_escape_string($a_r_sprite['sprite_name']) . "';\n\n";

      print "document.edit.r_sprite_number.value = '"   . mysql_real_escape_string($a_r_sprite['r_sprite_number'])   . "';\n";
      print "document.edit.r_sprite_level.value = '"    . mysql_real_escape_string($a_r_sprite['r_sprite_level'])    . "';\n";
      print "document.edit.r_sprite_tasks.value = '"    . mysql_real_escape_string($a_r_sprite['r_sprite_tasks'])    . "';\n";

      if ($a_r_sprite['r_sprite_registered']) {
        print "document.edit.r_sprite_registered.checked = true;\n";
      } else {
        print "document.edit.r_sprite_registered.checked = false;\n";
      }

      print "document.edit.r_sprite_id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
