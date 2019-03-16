<?php
# Script: weapons.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "weapons.php";

  logaccess($formVars['username'], $package, "Accessing the script");

  $formVars['group'] = 0;
  if (isset($_GET['group'])) {
    $formVars['group'] = clean($_GET['group'], 10);
  }
  $formVars['opposed'] = 0;
  if (isset($_GET['opposed'])) {
    $formVars['opposed'] = clean($_GET['opposed'], 10);
  }
  $formVars['accessory'] = 'No';
  if (isset($_GET['accessory'])) {
    $formVars['accessory'] = clean($_GET['accessory'], 10);
  }
  $formVars['ammunition'] = 'No';
  if (isset($_GET['ammunition'])) {
    $formVars['ammunition'] = clean($_GET['ammunition'], 10);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Weapon Listing</title>

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
    $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_groups = mysql_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">" . $groupname . "Firearms Information</th>";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearms-help');\">Help</a></th>";
  print "</tr>";
  print "</table>";

  print "<div id=\"firearms-help\" style=\"display: none\">";

  print "<div class=\"main-help ui-widget-content\">";

  print "<p>Help</p>";

  print "</div>";

  print "</div>";


  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Owner</th>";
  print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
  print "  <th class=\"ui-state-default\">Class</th>";
  print "  <th class=\"ui-state-default\">Name</th>";
  print "  <th class=\"ui-state-default\">Accuracy</th>";
  print "  <th class=\"ui-state-default\">Damage</th>";
  print "  <th class=\"ui-state-default\">AP</th>";
  print "  <th class=\"ui-state-default\">Mode</th>";
  print "  <th class=\"ui-state-default\">RC</th>";
  print "  <th class=\"ui-state-default\">Ammo</th>";
  print "  <th class=\"ui-state-default\">Book/Page</th>";
  print "</tr>";

  $q_string  = "select r_fa_id,r_fa_character,class_name,fa_name,fa_acc,fa_damage,";
  $q_string .= "fa_type,fa_flag,fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,";
  $q_string .= "fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,usr_last,usr_first,runr_name,";
  $q_string .= "runr_archetype,ver_book,fa_page ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join runners on runners.runr_id = r_firearms.r_fa_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "left join class on class.class_id = firearms.fa_class ";
  $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
  $q_string .= "order by runr_name,class_name,fa_name,ver_version ";
  $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_firearms) > 0) {
    while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_firearms['r_fa_character'] . " ";
        $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_members) > 0) {
          $display = "Yes";
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel(1)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($a_r_firearms['r_fa_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_firearms['r_fa_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {

        $damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

        $ap = return_Penetrate($a_r_firearms['fa_ap']);

        $mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

        $rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

        $ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

        $book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

        print "<tr>";
        print "<td class=\"ui-widget-content\">"        . $a_r_firearms['usr_first'] . " " . $a_r_firearms['usr_last'] . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_firearms['runr_name'] . " (" . $a_r_firearms['runr_archetype'] . ")" . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_firearms['class_name']                                  . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_firearms['fa_name']                                     . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $a_r_firearms['fa_acc']                                      . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $damage                                                      . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $ap                                                          . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $mode                                                        . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $rc                                                          . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $ammo                                                        . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $book                                                        . "</td>";
        print "</tr>";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
        if ($formVars['accessory'] == 'Yes') {
          $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $a_r_firearms['r_fa_character'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by acc_name,acc_rating ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $acc_name = $a_r_accessory['acc_name'];
              if ($a_r_accessory['acc_mount'] != '') {
                $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
              }

              $class = "ui-widget-content";

              print "<tr>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name                                             . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "</tr>\n";
            }
          }
        }


# associate any ammo with the weapon
        if ($formVars['ammunition'] == 'Yes') {
          $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
          $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
          $q_string .= "from r_ammo ";
          $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
          $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
          $q_string .= "where r_ammo_character = " . $a_r_firearms['r_fa_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by ammo_name ";
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

              $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

              $class = "ui-widget-content";

              print "<tr>\n";
              print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              print "</tr>\n";
            }
          }
        }
      }
    }
  }

  print "</table>\n";

  $and = "?";
  $display = "";
  if ($formVars['group'] > 0) {
    $display .= $and . "group=" . $formVars['group'];
    $and = "&";
  }
  if ($formVars['opposed'] != 0) {
    $display .= $and . "opposed=" . $formVars['opposed'];
    $and = "&";
  }
  print "<p class=\"ui-widget-content\">";
  print "<a href=\"weapons.php" . $display . "\">Reset Display</a>";
  print ", <a href=\"weapons.php" . $display . $and . "ammunition=Yes\">Show Ammunition</a>";
  print ", <a href=\"weapons.php" . $display . $and . "accessory=Yes\">Show Accessories</a>";
  print ", <a href=\"weapons.php" . $display . $and . "ammunition=Yes&accessory=Yes\">Show Both</a>";
  print "</p>\n";

?>

</div>

<?php

  if ($formVars['opposed'] > -1 ) {
    print "<div class=\"main\">\n";

    if ($formVars['opposed'] > 0) {
      $q_string  = "select grp_name ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['opposed'] . " ";
      $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysql_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">" . $groupname . "Firearms Information</th>";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearms-opposed-help');\">Help</a></th>";
    print "</tr>";
    print "</table>";

    print "<div id=\"firearms-opposed-help\" style=\"display: none\">";

    print "<div class=\"main-help ui-widget-content\">";

    print "<p>Help</p>";

    print "</div>";

    print "</div>";


    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Owner</th>";
    print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
    print "  <th class=\"ui-state-default\">Class</th>";
    print "  <th class=\"ui-state-default\">Name</th>";
    print "  <th class=\"ui-state-default\">Accuracy</th>";
    print "  <th class=\"ui-state-default\">Damage</th>";
    print "  <th class=\"ui-state-default\">AP</th>";
    print "  <th class=\"ui-state-default\">Mode</th>";
    print "  <th class=\"ui-state-default\">RC</th>";
    print "  <th class=\"ui-state-default\">Ammo</th>";
    print "  <th class=\"ui-state-default\">Book/Page</th>";
    print "</tr>";

    $q_string  = "select r_fa_id,r_fa_character,class_name,fa_name,fa_acc,fa_damage,";
    $q_string .= "fa_type,fa_flag,fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,";
    $q_string .= "fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,usr_last,usr_first,runr_name,";
    $q_string .= "runr_archetype,ver_book,fa_page ";
    $q_string .= "from r_firearms ";
    $q_string .= "left join runners on runners.runr_id = r_firearms.r_fa_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
    $q_string .= "left join class on class.class_id = firearms.fa_class ";
    $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
    $q_string .= "order by runr_name,class_name,fa_name,ver_version ";
    $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_firearms) > 0) {
      while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_firearms['r_fa_character'] . " ";
          $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_members) > 0) {
            $display = "Yes";
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel(1)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($a_r_firearms['r_fa_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_firearms['r_fa_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {

          $owned = 'yes';

          $damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

          $ap = return_Penetrate($a_r_firearms['fa_ap']);

          $mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

          $rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

          $ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

          $book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

          print "<tr>";
          print "<td class=\"ui-widget-content\">"        . $a_r_firearms['usr_first'] . " " . $a_r_firearms['usr_last'] . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_firearms['runr_name'] . " (" . $a_r_firearms['runr_archetype'] . ")" . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_firearms['class_name']                                  . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_firearms['fa_name']                                     . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $a_r_firearms['fa_acc']                                      . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $damage                                                      . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $ap                                                          . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $mode                                                        . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $rc                                                          . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $ammo                                                        . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $book                                                        . "</td>";
          print "</tr>";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          if ($formVars['accessory'] == 'Yes') {
            $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount ";
            $q_string .= "from r_accessory ";
            $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
            $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
            $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $a_r_firearms['r_fa_character'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
            $q_string .= "order by acc_name,acc_rating ";
            $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_accessory) > 0) {
              while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

                $acc_name = $a_r_accessory['acc_name'];
                if ($a_r_accessory['acc_mount'] != '') {
                  $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
                }

                $class = "ui-widget-content";

                print "<tr>\n";
                print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
                print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
                print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
                print "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name                                             . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
                print "</tr>\n";
              }
            }
          }


# associate any ammo with the weapon
          if ($formVars['ammunition'] == 'Yes') {
            $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,ammo_name,ammo_rounds,ammo_mod,ammo_ap ";
            $q_string .= "from r_ammo ";
            $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
            $q_string .= "where r_ammo_character = " . $a_r_firearms['r_fa_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
            $q_string .= "order by ammo_name ";
            $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_ammo) > 0) {
              while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

                $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

                $class = "ui-widget-content";

                print "<tr>\n";
                print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                print "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
                print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                print "</tr>\n";
              }
            }
          }
        }
      }
    }

    print "</table>\n";

    $and = "?";
    $display = "";
    if ($formVars['group'] > 0) {
      $display .= $and . "group=" . $formVars['group'];
      $and = "&";
    }
    if ($formVars['opposed'] != 0) {
      $display .= $and . "opposed=" . $formVars['opposed'];
      $and = "&";
    }
    print "<p class=\"ui-widget-content\">";
    print "<a href=\"weapons.php" . $display . "\">Reset Display</a>";
    print ", <a href=\"weapons.php" . $display . $and . "ammunition=Yes\">Show Ammunition</a>";
    print ", <a href=\"weapons.php" . $display . $and . "accessory=Yes\">Show Accessories</a>";
    print ", <a href=\"weapons.php" . $display . $and . "ammunition=Yes&accessory=Yes\">Show Both</a>";
    print "</p>\n";

    print "</div>\n";
  }
?>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
