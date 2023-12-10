<?php
# Script: mooks.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the table for editing.

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "mooks.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from inventory");

      $q_string  = "select runr_aliases,runr_name,runr_archetype,runr_agility,runr_body,runr_reaction,runr_strength,";
      $q_string .= "runr_charisma,runr_intuition,runr_logic,runr_willpower,runr_metatype,runr_essence,runr_totaledge,";
      $q_string .= "runr_currentedge,runr_magic,runr_initiate,runr_resonance,runr_age,runr_sex,runr_height,runr_weight,";
      $q_string .= "runr_physicalcon,";
      $q_string .= "runr_stuncon,runr_desc,runr_sop,runr_available ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'];
      $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_runners = mysqli_fetch_array($q_runners);

      if (mysqli_num_rows($q_runners) > 0) {

        print "document.edit.runr_sex['"       . $a_runners['runr_sex'] . "'].selected = true; \n";

        print "document.edit.runr_metatype.value = '"     . mysqli_real_escape_string($db, $a_runners['runr_metatype'])     . "';\n";
        print "document.edit.runr_aliases.value = '"      . mysqli_real_escape_string($db, $a_runners['runr_aliases'])      . "';\n";
        print "document.edit.runr_archetype.value = '"    . mysqli_real_escape_string($db, $a_runners['runr_archetype'])    . "';\n";
        print "document.edit.runr_name.value = '"         . mysqli_real_escape_string($db, $a_runners['runr_name'])         . "';\n";
        print "document.edit.runr_currentedge.value = '"  . mysqli_real_escape_string($db, $a_runners['runr_currentedge'])  . "';\n";

        print "document.edit.runr_agility.value = '"      . mysqli_real_escape_string($db, $a_runners['runr_agility'])      . "';\n";
        print "document.edit.runr_body.value = '"         . mysqli_real_escape_string($db, $a_runners['runr_body'])         . "';\n";
        print "document.edit.runr_reaction.value = '"     . mysqli_real_escape_string($db, $a_runners['runr_reaction'])     . "';\n";
        print "document.edit.runr_strength.value = '"     . mysqli_real_escape_string($db, $a_runners['runr_strength'])     . "';\n";
        print "document.edit.runr_charisma.value = '"     . mysqli_real_escape_string($db, $a_runners['runr_charisma'])     . "';\n";
        print "document.edit.runr_intuition.value = '"    . mysqli_real_escape_string($db, $a_runners['runr_intuition'])    . "';\n";
        print "document.edit.runr_logic.value = '"        . mysqli_real_escape_string($db, $a_runners['runr_logic'])        . "';\n";
        print "document.edit.runr_willpower.value = '"    . mysqli_real_escape_string($db, $a_runners['runr_willpower'])    . "';\n";
        print "document.edit.runr_totaledge.value = '"    . mysqli_real_escape_string($db, $a_runners['runr_totaledge'])    . "';\n";
        print "document.edit.runr_essence.value = '"      . mysqli_real_escape_string($db, $a_runners['runr_essence'])      . "';\n";
        print "document.edit.runr_magic.value = '"        . mysqli_real_escape_string($db, $a_runners['runr_magic'])        . "';\n";
        print "document.edit.runr_initiate.value = '"     . mysqli_real_escape_string($db, $a_runners['runr_initiate'])     . "';\n";
        print "document.edit.runr_resonance.value = '"    . mysqli_real_escape_string($db, $a_runners['runr_resonance'])    . "';\n";

        print "document.edit.runr_weight.value = '"       . mysqli_real_escape_string($db, $a_runners['runr_weight'])       . "';\n";
        print "document.edit.runr_height.value = '"       . mysqli_real_escape_string($db, $a_runners['runr_height'])       . "';\n";
        print "document.edit.runr_age.value = '"          . mysqli_real_escape_string($db, $a_runners['runr_age'])          . "';\n";

        print "document.edit.id.value = " . $formVars['id'] . ";\n";

        print "document.edit.update.disabled = false;\n";

        print "check_runner();\n";

      } else {
        print "document.edit.runr_name.value = 'Blank';\n";
      }

      mysqli_free_result($q_runners);
    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
