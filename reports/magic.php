<?php
# Script: magic.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "magic.php";

  logaccess($formVars['username'], $package, "Accessing the script");

  $formVars['group'] = 0;
  if (isset($_GET['group'])) {
    $formVars['group'] = clean($_GET['group'], 10);
  }
  $formVars['opposed'] = 0;
  if (isset($_GET['opposed'])) {
    $formVars['opposed'] = clean($_GET['opposed'], 10);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Magic Listing</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function () {
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<?php

  if ($formVars['group'] != '') {
    $q_string  = "select grp_name ";
    $q_string .= "from groups ";
    $q_string .= "where grp_id = " . $formVars['group'] . " ";
    $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_groups = mysqli_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">" . $groupname . "Magic Information</th>";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('magic-help');\">Help</a></th>";
  print "</tr>";
  print "</table>";

  print "<div id=\"magic-help\" style=\"display: none\">";

  print "<div class=\"main-help ui-widget-content\">";

  print "<p>Help</p>";

  print "</div>";

  print "</div>";

# want to get:
# a list of spells
# tradition
# mentor spirit
# spirits in general

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print   "<th class=\"ui-state-default\">Owner</th>\n";
  print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print   "<th class=\"ui-state-default\">Group</th>\n";
  print   "<th class=\"ui-state-default\">Name</th>\n";
  print   "<th class=\"ui-state-default\">Class</th>\n";
  print   "<th class=\"ui-state-default\">Type</th>\n";
  print   "<th class=\"ui-state-default\">Test</th>\n";
  print   "<th class=\"ui-state-default\">Range</th>\n";
  print   "<th class=\"ui-state-default\">Damage</th>\n";
  print   "<th class=\"ui-state-default\">Duration</th>\n";
  print   "<th class=\"ui-state-default\">Drain</th>\n";
  print   "<th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select r_spell_id,r_spell_character,r_spell_special,spell_name,spell_group,";
  $q_string .= "class_name,spell_class,spell_type,spell_test,spell_range,usr_last,usr_first,";
  $q_string .= "runr_name,runr_archetype,spell_damage,spell_duration,spell_drain,ver_book,spell_page ";
  $q_string .= "from r_spells ";
  $q_string .= "left join runners on runners.runr_id = r_spells.r_spell_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
  $q_string .= "left join class on class.class_id = spells.spell_group ";
  $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
  $q_string .= "order by runr_name,spell_group,spell_name ";
  $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_spells) > 0) {
    while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_spells['r_spell_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_spells['r_spell_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_spells['r_spell_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $spell_name = $a_r_spells['spell_name'];
        if (strlen($a_r_spells['r_spell_special']) > 0) {
          $spell_name = $a_r_spells['spell_name'] . " (" . $a_r_spells['r_spell_special'] . ")";
        }

        $spell_drain = "F" . $a_r_spells['spell_drain'];
        if ($a_r_spells['spell_drain'] == 0) {
          $spell_drain = "F";
        }

        $spell_book = return_Book($a_r_spells['ver_book'], $a_r_spells['spell_page']);

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spells['usr_first'] . " " . $a_r_spells['usr_last']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spells['runr_name'] . " (" . $a_r_spells['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spells['class_name']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spells['spell_name']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spells['spell_class']    . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_type']     . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_test']     . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_range']    . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_damage']   . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_duration'] . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spell_drain                . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spell_book                 . "</td>\n";
        print "</tr>\n";
      }
    }
  }
  print "</table>\n";

  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_s_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    $tradition_name[$a_s_tradition['s_trad_id']] = $a_s_tradition['s_trad_name'];
  }

  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysqli_fetch_array($q_attributes)) {
    $attribute_name[$a_attributes['att_id']] = $a_attributes['att_name'];
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print   "<th class=\"ui-state-default\">Owner</th>\n";
  print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print   "<th class=\"ui-state-default\">Tradition</th>\n";
  print   "<th class=\"ui-state-default\">Description</th>\n";
  print   "<th class=\"ui-state-default\">Combat</th>\n";
  print   "<th class=\"ui-state-default\">Detection</th>\n";
  print   "<th class=\"ui-state-default\">Health</th>\n";
  print   "<th class=\"ui-state-default\">Illusion</th>\n";
  print   "<th class=\"ui-state-default\">Manipulation</th>\n";
  print   "<th class=\"ui-state-default\">Drain</th>\n";
  print   "<th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select r_trad_id,r_trad_character,trad_id,trad_name,trad_description,trad_combat,";
  $q_string .= "trad_detection,trad_health,trad_illusion,trad_manipulation,trad_drainleft,";
  $q_string .= "trad_drainright,usr_last,usr_first,runr_name,runr_archetype,ver_book,trad_page ";
  $q_string .= "from r_tradition ";
  $q_string .= "left join runners on runners.runr_id = r_tradition.r_trad_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
  $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
  $q_string .= "order by trad_name ";
  $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_tradition) > 0) {
    while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_tradition['r_trad_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_tradition['r_trad_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_tradition['r_trad_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $trad_book = return_Book($a_r_tradition['ver_book'], $a_r_tradition['trad_page']);

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_tradition['usr_first'] . " " . $a_r_tradition['usr_last']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_tradition['runr_name'] . " (" . $a_r_tradition['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_tradition['trad_name']                                    . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_tradition['trad_description']                             . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_combat']]                 . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_detection']]              . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_health']]                 . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_illusion']]               . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_manipulation']]           . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $attribute_name[$a_r_tradition['trad_drainleft']] . " + " . $attribute_name[$a_r_tradition['trad_drainright']] . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $trad_book                                                     . "</td>\n";
        print "</tr>\n";
      }
    }
  }
  print "</table>\n";


  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print   "<th class=\"ui-state-default\">Owner</th>\n";
  print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print   "<th class=\"ui-state-default\">Mentor Spirit</th>\n";
  print   "<th class=\"ui-state-default\">Advantages For All</th>\n";
  print   "<th class=\"ui-state-default\">Mage Advantages</th>\n";
  print   "<th class=\"ui-state-default\">Disadvantages</th>\n";
  print   "<th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select r_mentor_id,r_mentor_character,mentor_id,mentor_name,mentor_all,";
  $q_string .= "mentor_mage,mentor_disadvantage,ver_book,mentor_page,";
  $q_string .= "usr_last,usr_first,runr_name,runr_archetype ";
  $q_string .= "from r_mentor ";
  $q_string .= "left join runners on runners.runr_id = r_mentor.r_mentor_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
  $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
  $q_string .= "order by mentor_name ";
  $q_r_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_mentor) > 0) {
    while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_mentor['r_mentor_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_mentor['r_mentor_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_mentor['r_mentor_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $mentor_book = return_Book($a_r_mentor['ver_book'], $a_r_mentor['mentor_page']);

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['usr_first'] . " " . $a_r_mentor['usr_last']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['runr_name'] . "( " . $a_r_mentor['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_name']            . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_all']             . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_mage']            . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_disadvantage']    . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $mentor_book                          . "</td>\n";
        print "</tr>\n";

      }
    }
  }
  print "</table>\n";


  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print   "<th class=\"ui-state-default\">Owner</th>\n";
  print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
  print   "<th class=\"ui-state-default\">Spirit Name</th>\n";
  print   "<th class=\"ui-state-default\">Force</th>\n";
  print   "<th class=\"ui-state-default\">Services</th>\n";
  print   "<th class=\"ui-state-default\">Bound</th>\n";
  print   "<th class=\"ui-state-default\">Body</th>\n";
  print   "<th class=\"ui-state-default\">Agility</th>\n";
  print   "<th class=\"ui-state-default\">Reaction</th>\n";
  print   "<th class=\"ui-state-default\">Strength</th>\n";
  print   "<th class=\"ui-state-default\">Willpower</th>\n";
  print   "<th class=\"ui-state-default\">Logic</th>\n";
  print   "<th class=\"ui-state-default\">Intuition</th>\n";
  print   "<th class=\"ui-state-default\">Charisma</th>\n";
  print   "<th class=\"ui-state-default\">Edge</th>\n";
  print   "<th class=\"ui-state-default\">Essence</th>\n";
  print   "<th class=\"ui-state-default\">Magic</th>\n";
  print   "<th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select spirit_id,r_spirit_id,r_spirit_character,r_spirit_force,r_spirit_services,r_spirit_bound,";
  $q_string .= "spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
  $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,usr_last,usr_first,runr_name,runr_archetype,";
  $q_string .= "spirit_edge,spirit_essence,spirit_magic,ver_book,spirit_page ";
  $q_string .= "from r_spirit ";
  $q_string .= "left join runners on runners.runr_id = r_spirit.r_spirit_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
  $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
  $q_string .= "order by spirit_name ";
  $q_r_spirit = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_spirit) > 0) {
    while ($a_r_spirit = mysqli_fetch_array($q_r_spirit)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_spirit['r_spirit_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_spirit['r_spirit_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_spirit['r_spirit_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $spirit_body      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_body']);
        $spirit_agility   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_agility']);
        $spirit_reaction  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_reaction']);
        $spirit_strength  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_strength']);
        $spirit_willpower = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_willpower']);
        $spirit_logic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_logic']);
        $spirit_intuition = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_intuition']);
        $spirit_charisma  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_charisma']);
        $spirit_edge      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_edge']);
        $spirit_essence   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_essence']);
        $spirit_magic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_magic']);

        $spirit_book = return_Book($a_r_spirit['ver_book'], $a_r_spirit['spirit_page']);

        if ($a_r_spirit['r_spirit_bound']) {
          $bound = 'Yes';
        } else {
          $bound = 'No';
        }

        $class = "ui-widget-content";

        print "<tr>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spirit['usr_first'] . " " . $a_r_mentor['usr_last']     . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spirit['runr_name'] . " (" . $a_r_spirit['runr_archetype'] . ")" . "</td>\n";
        print "  <td class=\"" . $class . "\">"        . $a_r_spirit['spirit_name']            . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spirit['r_spirit_force']         . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $a_r_spirit['r_spirit_services']      . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $bound                                . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_body                          . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_agility                       . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_reaction                      . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_strength                      . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_willpower                     . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_logic                         . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_intuition                     . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_charisma                      . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_edge                          . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_essence                       . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_magic                         . "</td>\n";
        print "  <td class=\"" . $class . " delete\">" . $spirit_book                          . "</td>\n";
        print "</tr>\n";


        $active = "Active Skills (Dice Pool): ";
        $a_comma = "";
        $q_string  = "select act_name,att_column,ver_book,act_page ";
        $q_string .= "from sp_active ";
        $q_string .= "left join active on active.act_id = sp_active.sp_act_number ";
        $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
        $q_string .= "left join versions on versions.ver_id = active.act_book ";
        $q_string .= "where sp_act_creature = " . $a_r_spirit['spirit_id'] . " ";
        $q_string .= "order by act_name ";
        $q_sp_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_sp_active) > 0) {
          while ($a_sp_active = mysqli_fetch_array($q_sp_active)) {

            if ($a_sp_active['att_column'] == "runr_body") {
              $att_column = "spirit_body";
            }
            if ($a_sp_active['att_column'] == "runr_agility") {
              $att_column = "spirit_agility";
            }
            if ($a_sp_active['att_column'] == "runr_reaction") {
              $att_column = "spirit_reaction";
            }
            if ($a_sp_active['att_column'] == "runr_strength") {
              $att_column = "spirit_strength";
            }
            if ($a_sp_active['att_column'] == "runr_willpower") {
              $att_column = "spirit_willpower";
            }
            if ($a_sp_active['att_column'] == "runr_logic") {
              $att_column = "spirit_logic";
            }
            if ($a_sp_active['att_column'] == "runr_intuition") {
              $att_column = "spirit_intuition";
            }
            if ($a_sp_active['att_column'] == "runr_charisma") {
              $att_column = "spirit_charisma";
            }

            $q_string  = "select " . $att_column . " ";
            $q_string .= "from spirits ";
            $q_string .= "where spirit_id = " . $a_r_spirit['spirit_id'] . " ";
            $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            $a_spirits = mysqli_fetch_array($q_spirits);

            if ($a_r_spirit['r_spirit_force'] == 0) {
              $active .= $a_comma . $a_sp_active['act_name'] . " (F+(" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . "))";
            } else {
              $active .= $a_comma . $a_sp_active['act_name'] . " (" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . ")";
            }
            $a_comma = ", ";

            $active_book = return_Book($a_sp_active['ver_book'], $a_sp_active['act_page']);

          }
          print "<tr>\n";
          print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $active . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $active_book . "</td>\n";
          print "</tr>\n";
        }


        $powers = "Powers: ";
        $p_comma = "";
        $optional = "Optional Powers: ";
        $o_comma = "";
        $q_string  = "select sp_power_id,sp_power_specialize,sp_power_optional,pow_name,ver_book,pow_page ";
        $q_string .= "from sp_powers ";
        $q_string .= "left join powers on powers.pow_id = sp_powers.sp_power_number ";
        $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
        $q_string .= "where sp_power_creature = " . $a_r_spirit['spirit_id'] . " ";
        $q_string .= "order by sp_power_optional,pow_name ";
        $q_sp_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_sp_powers) > 0) {
          while ($a_sp_powers = mysqli_fetch_array($q_sp_powers)) {

            if ($a_sp_powers['sp_power_optional']) {
              $optional .= $o_comma . $a_sp_powers['pow_name'];
              $o_comma = ", ";
            } else {
              $powers .= $p_comma . $a_sp_powers['pow_name'];
              $p_comma = ", ";
            }

            $power_book = return_Book($a_sp_powers['ver_book'], $a_sp_powers['pow_page']);
          }
          print "<tr>\n";
          print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $powers . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
          print "</tr>\n";
          print "<tr>\n";
          print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $optional . "</td>\n";
          print "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
          print "</tr>\n";
        }
      }
    }
  }
  print "</table>\n";

?>

</div>


<?php

  if ($formVars['opposed'] > -1 ) {
    print "<div class=\"main\">\n";

    if ($formVars['opposed'] != '') {
      $q_string  = "select grp_name ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['opposed'] . " ";
      $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysqli_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">" . $groupname . "Magic Information</th>";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('opposed-help');\">Help</a></th>";
    print "</tr>";
    print "</table>";

    print "<div id=\"opposed-help\" style=\"display: none\">";

    print "<div class=\"main-help ui-widget-content\">";

    print "<p>Help</p>";

    print "</div>";

    print "</div>";

# want to get:
# a list of spells
# tradition
# mentor spirit
# spirits in general

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print   "<th class=\"ui-state-default\">Owner</th>\n";
    print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print   "<th class=\"ui-state-default\">Group</th>\n";
    print   "<th class=\"ui-state-default\">Name</th>\n";
    print   "<th class=\"ui-state-default\">Class</th>\n";
    print   "<th class=\"ui-state-default\">Type</th>\n";
    print   "<th class=\"ui-state-default\">Test</th>\n";
    print   "<th class=\"ui-state-default\">Range</th>\n";
    print   "<th class=\"ui-state-default\">Damage</th>\n";
    print   "<th class=\"ui-state-default\">Duration</th>\n";
    print   "<th class=\"ui-state-default\">Drain</th>\n";
    print   "<th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>\n";

    $q_string  = "select r_spell_id,r_spell_character,r_spell_special,spell_name,spell_group,";
    $q_string .= "class_name,spell_class,spell_type,spell_test,spell_range,usr_last,usr_first,";
    $q_string .= "runr_name,runr_archetype,spell_damage,spell_duration,spell_drain,ver_book,spell_page ";
    $q_string .= "from r_spells ";
    $q_string .= "left join runners on runners.runr_id = r_spells.r_spell_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
    $q_string .= "left join class on class.class_id = spells.spell_group ";
    $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
    $q_string .= "order by runr_name,spell_group,spell_name ";
    $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_spells) > 0) {
      while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_spells['r_spell_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_spells['r_spell_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_spells['r_spell_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $spell_name = $a_r_spells['spell_name'];
          if (strlen($a_r_spells['r_spell_special']) > 0) {
            $spell_name = $a_r_spells['spell_name'] . " (" . $a_r_spells['r_spell_special'] . ")";
          }

          $spell_drain = "F" . $a_r_spells['spell_drain'];
          if ($a_r_spells['spell_drain'] == 0) {
            $spell_drain = "F";
          }

          $spell_book = return_Book($a_r_spells['ver_book'], $a_r_spells['spell_page']);

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spells['usr_first'] . " " . $a_r_spells['usr_last']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spells['runr_name'] . " (" . $a_r_spells['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spells['class_name']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spells['spell_name']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spells['spell_class']    . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_type']     . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_test']     . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_range']    . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_damage']   . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spells['spell_duration'] . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spell_drain                . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spell_book                 . "</td>\n";
          print "</tr>\n";
        }
      }
    }
    print "</table>\n";

    $q_string  = "select s_trad_id,s_trad_name ";
    $q_string .= "from s_tradition ";
    $q_s_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
      $tradition_name[$a_s_tradition['s_trad_id']] = $a_s_tradition['s_trad_name'];
    }

    $q_string  = "select att_id,att_name ";
    $q_string .= "from attributes ";
    $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_attributes = mysqli_fetch_array($q_attributes)) {
      $attribute_name[$a_attributes['att_id']] = $a_attributes['att_name'];
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print   "<th class=\"ui-state-default\">Owner</th>\n";
    print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print   "<th class=\"ui-state-default\">Tradition</th>\n";
    print   "<th class=\"ui-state-default\">Description</th>\n";
    print   "<th class=\"ui-state-default\">Combat</th>\n";
    print   "<th class=\"ui-state-default\">Detection</th>\n";
    print   "<th class=\"ui-state-default\">Health</th>\n";
    print   "<th class=\"ui-state-default\">Illusion</th>\n";
    print   "<th class=\"ui-state-default\">Manipulation</th>\n";
    print   "<th class=\"ui-state-default\">Drain</th>\n";
    print   "<th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>\n";

    $q_string  = "select r_trad_id,r_trad_character,trad_id,trad_name,trad_description,trad_combat,";
    $q_string .= "trad_detection,trad_health,trad_illusion,trad_manipulation,trad_drainleft,";
    $q_string .= "trad_drainright,usr_last,usr_first,runr_name,runr_archetype,ver_book,trad_page ";
    $q_string .= "from r_tradition ";
    $q_string .= "left join runners on runners.runr_id = r_tradition.r_trad_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
    $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
    $q_string .= "order by trad_name ";
    $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_tradition) > 0) {
      while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_tradition['r_trad_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_tradition['r_trad_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_tradition['r_trad_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $trad_book = return_Book($a_r_tradition['ver_book'], $a_r_tradition['trad_page']);

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_tradition['usr_first'] . " " . $a_r_tradition['usr_last']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_tradition['runr_name'] . " (" . $a_r_tradition['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_tradition['trad_name']                                    . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_tradition['trad_description']                             . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_combat']]                 . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_detection']]              . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_health']]                 . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_illusion']]               . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $tradition_name[$a_r_tradition['trad_manipulation']]           . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $attribute_name[$a_r_tradition['trad_drainleft']] . " + " . $attribute_name[$a_r_tradition['trad_drainright']] . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $trad_book                                                     . "</td>\n";
          print "</tr>\n";
        }
      }
    }
    print "</table>\n";


    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print   "<th class=\"ui-state-default\">Owner</th>\n";
    print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print   "<th class=\"ui-state-default\">Mentor Spirit</th>\n";
    print   "<th class=\"ui-state-default\">Advantages For All</th>\n";
    print   "<th class=\"ui-state-default\">Mage Advantages</th>\n";
    print   "<th class=\"ui-state-default\">Disadvantages</th>\n";
    print   "<th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>\n";

    $q_string  = "select r_mentor_id,r_mentor_character,mentor_id,mentor_name,mentor_all,";
    $q_string .= "mentor_mage,mentor_disadvantage,ver_book,mentor_page,";
    $q_string .= "usr_last,usr_first,runr_name,runr_archetype ";
    $q_string .= "from r_mentor ";
    $q_string .= "left join runners on runners.runr_id = r_mentor.r_mentor_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join mentor on mentor.mentor_id = r_mentor.r_mentor_number ";
    $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
    $q_string .= "order by mentor_name ";
    $q_r_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_mentor) > 0) {
      while ($a_r_mentor = mysqli_fetch_array($q_r_mentor)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_mentor['r_mentor_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_mentor['r_mentor_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_mentor['r_mentor_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $mentor_book = return_Book($a_r_mentor['ver_book'], $a_r_mentor['mentor_page']);

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['usr_first'] . " " . $a_r_mentor['usr_last']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['runr_name'] . "( " . $a_r_mentor['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_name']            . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_all']             . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_mage']            . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_mentor['mentor_disadvantage']    . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $mentor_book                          . "</td>\n";
          print "</tr>\n";

        }
      }
    }
    print "</table>\n";


    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print   "<th class=\"ui-state-default\">Owner</th>\n";
    print   "<th class=\"ui-state-default\">Runner (Archetype)</th>\n";
    print   "<th class=\"ui-state-default\">Spirit Name</th>\n";
    print   "<th class=\"ui-state-default\">Force</th>\n";
    print   "<th class=\"ui-state-default\">Services</th>\n";
    print   "<th class=\"ui-state-default\">Bound</th>\n";
    print   "<th class=\"ui-state-default\">Body</th>\n";
    print   "<th class=\"ui-state-default\">Agility</th>\n";
    print   "<th class=\"ui-state-default\">Reaction</th>\n";
    print   "<th class=\"ui-state-default\">Strength</th>\n";
    print   "<th class=\"ui-state-default\">Willpower</th>\n";
    print   "<th class=\"ui-state-default\">Logic</th>\n";
    print   "<th class=\"ui-state-default\">Intuition</th>\n";
    print   "<th class=\"ui-state-default\">Charisma</th>\n";
    print   "<th class=\"ui-state-default\">Edge</th>\n";
    print   "<th class=\"ui-state-default\">Essence</th>\n";
    print   "<th class=\"ui-state-default\">Magic</th>\n";
    print   "<th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>\n";

    $q_string  = "select spirit_id,r_spirit_id,r_spirit_character,r_spirit_force,r_spirit_services,r_spirit_bound,";
    $q_string .= "spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
    $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,usr_last,usr_first,runr_name,runr_archetype,";
    $q_string .= "spirit_edge,spirit_essence,spirit_magic,ver_book,spirit_page ";
    $q_string .= "from r_spirit ";
    $q_string .= "left join runners on runners.runr_id = r_spirit.r_spirit_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join spirits on spirits.spirit_id = r_spirit.r_spirit_number ";
    $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
    $q_string .= "order by spirit_name ";
    $q_r_spirit = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_spirit) > 0) {
      while ($a_r_spirit = mysqli_fetch_array($q_r_spirit)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_spirit['r_spirit_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_spirit['r_spirit_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_spirit['r_spirit_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $spirit_body      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_body']);
          $spirit_agility   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_agility']);
          $spirit_reaction  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_reaction']);
          $spirit_strength  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_strength']);
          $spirit_willpower = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_willpower']);
          $spirit_logic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_logic']);
          $spirit_intuition = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_intuition']);
          $spirit_charisma  = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_charisma']);
          $spirit_edge      = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_edge']);
          $spirit_essence   = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_essence']);
          $spirit_magic     = return_Spirit($a_r_spirit['r_spirit_force'], $a_r_spirit['spirit_magic']);

          $spirit_book = return_Book($a_r_spirit['ver_book'], $a_r_spirit['spirit_page']);

          if ($a_r_spirit['r_spirit_bound']) {
            $bound = 'Yes';
          } else {
            $bound = 'No';
          }

          $class = "ui-widget-content";

          print "<tr>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spirit['usr_first'] . " " . $a_r_mentor['usr_last']     . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spirit['runr_name'] . " (" . $a_r_spirit['runr_archetype'] . ")" . "</td>\n";
          print "  <td class=\"" . $class . "\">"        . $a_r_spirit['spirit_name']            . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spirit['r_spirit_force']         . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $a_r_spirit['r_spirit_services']      . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $bound                                . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_body                          . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_agility                       . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_reaction                      . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_strength                      . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_willpower                     . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_logic                         . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_intuition                     . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_charisma                      . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_edge                          . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_essence                       . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_magic                         . "</td>\n";
          print "  <td class=\"" . $class . " delete\">" . $spirit_book                          . "</td>\n";
          print "</tr>\n";


          $active = "Active Skills (Dice Pool): ";
          $a_comma = "";
          $q_string  = "select act_name,att_column,ver_book,act_page ";
          $q_string .= "from sp_active ";
          $q_string .= "left join active on active.act_id = sp_active.sp_act_number ";
          $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
          $q_string .= "left join versions on versions.ver_id = active.act_book ";
          $q_string .= "where sp_act_creature = " . $a_r_spirit['spirit_id'] . " ";
          $q_string .= "order by act_name ";
          $q_sp_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sp_active) > 0) {
            while ($a_sp_active = mysqli_fetch_array($q_sp_active)) {

              if ($a_sp_active['att_column'] == "runr_body") {
                $att_column = "spirit_body";
              }
              if ($a_sp_active['att_column'] == "runr_agility") {
                $att_column = "spirit_agility";
              }
              if ($a_sp_active['att_column'] == "runr_reaction") {
                $att_column = "spirit_reaction";
              }
              if ($a_sp_active['att_column'] == "runr_strength") {
                $att_column = "spirit_strength";
              }
              if ($a_sp_active['att_column'] == "runr_willpower") {
                $att_column = "spirit_willpower";
              }
              if ($a_sp_active['att_column'] == "runr_logic") {
                $att_column = "spirit_logic";
              }
              if ($a_sp_active['att_column'] == "runr_intuition") {
                $att_column = "spirit_intuition";
              }
              if ($a_sp_active['att_column'] == "runr_charisma") {
                $att_column = "spirit_charisma";
              }

              $q_string  = "select " . $att_column . " ";
              $q_string .= "from spirits ";
              $q_string .= "where spirit_id = " . $a_r_spirit['spirit_id'] . " ";
              $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              $a_spirits = mysqli_fetch_array($q_spirits);

              if ($a_r_spirit['r_spirit_force'] == 0) {
                $active .= $a_comma . $a_sp_active['act_name'] . " (F+(" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . "))";
              } else {
                $active .= $a_comma . $a_sp_active['act_name'] . " (" . return_Spirit($a_r_spirit['r_spirit_force'], ($a_r_spirit['r_spirit_force'] + $a_spirits[$att_column])) . ")";
              }
              $a_comma = ", ";

              $active_book = return_Book($a_sp_active['ver_book'], $a_sp_active['act_page']);

            }
            print "<tr>\n";
            print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $active . "</td>\n";
            print "  <td class=\"ui-widget-content delete\">" . $active_book . "</td>\n";
            print "</tr>\n";
          }


          $powers = "Powers: ";
          $p_comma = "";
          $optional = "Optional Powers: ";
          $o_comma = "";
          $q_string  = "select sp_power_id,sp_power_specialize,sp_power_optional,pow_name,ver_book,pow_page ";
          $q_string .= "from sp_powers ";
          $q_string .= "left join powers on powers.pow_id = sp_powers.sp_power_number ";
          $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
          $q_string .= "where sp_power_creature = " . $a_r_spirit['spirit_id'] . " ";
          $q_string .= "order by sp_power_optional,pow_name ";
          $q_sp_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_sp_powers) > 0) {
            while ($a_sp_powers = mysqli_fetch_array($q_sp_powers)) {

              if ($a_sp_powers['sp_power_optional']) {
                $optional .= $o_comma . $a_sp_powers['pow_name'];
                $o_comma = ", ";
              } else {
                $powers .= $p_comma . $a_sp_powers['pow_name'];
                $p_comma = ", ";
              }

              $power_book = return_Book($a_sp_powers['ver_book'], $a_sp_powers['pow_page']);
            }
            print "<tr>\n";
            print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $powers . "</td>\n";
            print "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
            print "</tr>\n";
            print "<tr>\n";
            print "  <td class=\"ui-widget-content\" colspan=\"18\">" . $optional . "</td>\n";
            print "  <td class=\"ui-widget-content delete\">" . $power_book . "</td>\n";
            print "</tr>\n";
          }
        }
      }
    }
    print "</table>\n";

    print "</div>\n";
  }
?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
