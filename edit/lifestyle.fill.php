<?php
# Script: lifestyle.fill.php
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
    $package = "lifestyle.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_lifestyle");

      $q_string  = "select r_life_number,r_life_comforts,r_life_necessities,r_life_security,r_life_neighborhood,r_life_entertainment,r_life_space,r_life_desc,r_life_months ";
      $q_string .= "from r_lifestyle ";
      $q_string .= "where r_life_id = " . $formVars['id'];
      $q_r_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle);
      mysqli_free_result($q_r_lifestyle);

      print "document.edit.r_life_number.value = '"        . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_number'])        . "';\n";
      print "document.edit.r_life_comforts.value = '"      . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_comforts'])      . "';\n";
      print "document.edit.r_life_necessities.value = '"   . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_necessities'])   . "';\n";
      print "document.edit.r_life_security.value = '"      . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_security'])      . "';\n";
      print "document.edit.r_life_neighborhood.value = '"  . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_neighborhood'])  . "';\n";
      print "document.edit.r_life_entertainment.value = '" . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_entertainment']) . "';\n";
      print "document.edit.r_life_space.value = '"         . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_space'])         . "';\n";
      print "document.edit.r_life_desc.value = '"          . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_desc'])          . "';\n";
      print "document.edit.r_life_months.value = '"        . mysqli_real_escape_string($db, $a_r_lifestyle['r_life_months'])        . "';\n";

      print "document.edit.r_life_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_life_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
