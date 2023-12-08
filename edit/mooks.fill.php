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

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from inventory");

      $q_string  = "select runr_aliases,runr_name,runr_archetype,runr_agility,runr_body,runr_reaction,runr_strength,";
      $q_string .= "runr_charisma,runr_intuition,runr_logic,runr_willpower,runr_metatype,runr_essence,runr_totaledge,";
      $q_string .= "runr_currentedge,runr_magic,runr_initiate,runr_resonance,runr_age,runr_sex,runr_height,runr_weight,";
      $q_string .= "runr_physicalcon,";
      $q_string .= "runr_stuncon,runr_desc,runr_sop,runr_available ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'];
      $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_runners = mysqli_fetch_array($q_runners);

      if (mysql_num_rows($q_runners) > 0) {

        print "document.edit.runr_sex['"       . $a_runners['runr_sex'] . "'].selected = true; \n";

        print "document.edit.runr_metatype.value = '"     . mysql_real_escape_string($a_runners['runr_metatype'])     . "';\n";
        print "document.edit.runr_aliases.value = '"      . mysql_real_escape_string($a_runners['runr_aliases'])      . "';\n";
        print "document.edit.runr_archetype.value = '"    . mysql_real_escape_string($a_runners['runr_archetype'])    . "';\n";
        print "document.edit.runr_name.value = '"         . mysql_real_escape_string($a_runners['runr_name'])         . "';\n";
        print "document.edit.runr_currentedge.value = '"  . mysql_real_escape_string($a_runners['runr_currentedge'])  . "';\n";

        print "document.edit.runr_agility.value = '"      . mysql_real_escape_string($a_runners['runr_agility'])      . "';\n";
        print "document.edit.runr_body.value = '"         . mysql_real_escape_string($a_runners['runr_body'])         . "';\n";
        print "document.edit.runr_reaction.value = '"     . mysql_real_escape_string($a_runners['runr_reaction'])     . "';\n";
        print "document.edit.runr_strength.value = '"     . mysql_real_escape_string($a_runners['runr_strength'])     . "';\n";
        print "document.edit.runr_charisma.value = '"     . mysql_real_escape_string($a_runners['runr_charisma'])     . "';\n";
        print "document.edit.runr_intuition.value = '"    . mysql_real_escape_string($a_runners['runr_intuition'])    . "';\n";
        print "document.edit.runr_logic.value = '"        . mysql_real_escape_string($a_runners['runr_logic'])        . "';\n";
        print "document.edit.runr_willpower.value = '"    . mysql_real_escape_string($a_runners['runr_willpower'])    . "';\n";
        print "document.edit.runr_totaledge.value = '"    . mysql_real_escape_string($a_runners['runr_totaledge'])    . "';\n";
        print "document.edit.runr_essence.value = '"      . mysql_real_escape_string($a_runners['runr_essence'])      . "';\n";
        print "document.edit.runr_magic.value = '"        . mysql_real_escape_string($a_runners['runr_magic'])        . "';\n";
        print "document.edit.runr_initiate.value = '"     . mysql_real_escape_string($a_runners['runr_initiate'])     . "';\n";
        print "document.edit.runr_resonance.value = '"    . mysql_real_escape_string($a_runners['runr_resonance'])    . "';\n";

        print "document.edit.runr_weight.value = '"       . mysql_real_escape_string($a_runners['runr_weight'])       . "';\n";
        print "document.edit.runr_height.value = '"       . mysql_real_escape_string($a_runners['runr_height'])       . "';\n";
        print "document.edit.runr_age.value = '"          . mysql_real_escape_string($a_runners['runr_age'])          . "';\n";

        print "document.edit.id.value = " . $formVars['id'] . ";\n";

        print "document.edit.update.disabled = false;\n";

        print "check_runner();\n";

      } else {
        print "document.edit.runr_name.value = 'Blank';\n";
      }

      mysql_free_result($q_runners);
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
