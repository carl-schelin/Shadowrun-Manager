<?php
# Script: cyberware.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "cyberware.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script");

  $formVars['group'] = 0;
  if (isset($_GET['group'])) {
    $formVars['group'] = clean($_GET['group'], 10);
  }
  $formVars['opposed'] = 0;
  if (isset($_GET['opposed'])) {
    $formVars['opposed'] = clean($_GET['opposed'], 10);
  }
  $formVars['accessory'] = 'no';
  if (isset($_GET['accessory'])) {
    $formVars['accessory'] = clean($_GET['accessory'], 10);
  }
  $formVars['weapons'] = 'no';
  if (isset($_GET['weapons'])) {
    $formVars['weapons'] = clean($_GET['weapons'], 10);
  }
  $formVars['ammunition'] = 'no';
  if (isset($_GET['ammunition'])) {
    $formVars['ammunition'] = clean($_GET['ammunition'], 10);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cyberware Listing</title>

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
    $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_groups = mysqli_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">" . $groupname . "Cyberware Information</th>";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberware-help');\">Help</a></th>";
  print "</tr>";
  print "</table>";

  print "<div id=\"cyberware-help\" style=\"display: none\">";

  print "<div class=\"main-help ui-widget-content\">";

  print "<p>Help</p>";

  print "</div>";

  print "</div>";


  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Owner</th>";
  print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
  print "  <th class=\"ui-state-default\">Class</th>";
  print "  <th class=\"ui-state-default\">Cyberware</th>";
  print "  <th class=\"ui-state-default\">Rating</th>";
  print "  <th class=\"ui-state-default\">Essence</th>";
  print "  <th class=\"ui-state-default\">Capacity</th>";
  print "  <th class=\"ui-state-default\">Availability</th>";
  print "  <th class=\"ui-state-default\">Book/Page</th>";
  print "</tr>";

  $q_string  = "select r_ware_id,r_ware_character,class_name,ware_class,ware_name,ware_rating,";
  $q_string .= "ware_multiply,ware_essence,ware_capacity,ware_avail,ware_perm,usr_first,usr_last,";
  $q_string .= "runr_name,runr_archetype,grade_name,grade_essence,ver_book,ware_page ";
  $q_string .= "from r_cyberware ";
  $q_string .= "left join runners on runners.runr_id = r_cyberware.r_ware_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
  $q_string .= "left join class on class.class_id = cyberware.ware_class ";
  $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
  $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
  $q_strong .= "where ver_active = 1 ";
  $q_string .= "order by runr_name,ware_name ";
  $q_r_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberware) > 0) {
    while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {

      $display = 'No';

# are we looking at just one group and is the character a member of that group?
      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_cyberware['r_ware_character'] . " ";
        $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_members) > 0) {
          $display = 'Yes';
        }
      } else {
# I'm a Johnson, show me everyone in the group
        if (check_userlevel($db, $AL_Johnson)) {
          $display = 'Yes';
        }
# it's my character so show me no matter what
        if (check_owner($db, $a_r_cyberware['r_ware_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel($db, $AL_Fixer) && check_available($db, $a_r_cyberware['r_ware_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {
        $grade = '';
        if ($a_r_cyberware['grade_essence'] != 1.00) {
          $grade = " (" . $a_r_cyberware['grade_name'] . ")";
        }

        $rating = return_Rating($a_r_cyberware['ware_rating']);

        $capacity = return_Capacity($a_r_cyberware['ware_capacity']);

        $ware_essence = return_Essence($a_r_cyberware['ware_essence'] * $a_r_cyberware['grade_essence']);

        $avail = return_Avail($a_r_cyberware['ware_avail'], $a_r_cyberware['ware_perm']);

        $book = return_Book($a_r_cyberware['ver_book'], $a_r_cyberware['ware_page']);

        print "<tr>";
        print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['usr_first'] . " " . $a_r_cyberware['usr_last']   . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['runr_name'] . " (" . $a_r_cyberware['runr_archetype'] . ")" . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['class_name']                                     . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['ware_name'] . $grade                             . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $rating                                                          . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $ware_essence                                                    . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $capacity                                                        . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $avail                                                           . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $book                                                            . "</td>";
        print "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
        if ($formVars['accessory'] == 'Yes') {
          $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $a_r_cyberware['r_ware_character'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by acc_name,acc_rating ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $acc_name = $a_r_accessory['acc_name'];
              if ($a_r_accessory['acc_mount'] != '') {
                $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
              }

              $class = "ui-widget-content";

              $book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              print "<tr>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"            . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"            . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"            . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . "--"                . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"            . "</td>\n";
              print "  <td class=\"" . $class . " delete\">" . $book               . "</td>\n";
              print "</tr>\n";
            }
          }
        }

        if ($formVars['weapons'] == 'Yes') {
          $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
          $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
          $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
          $q_string .= "from r_firearms ";
          $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
          $q_string .= "left join class on class.class_id = firearms.fa_class ";
          $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
          $q_string .= "where r_fa_character = " . $a_r_cyberware['r_ware_character'] . " and r_fa_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by fa_name,fa_class ";
          $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_firearms) > 0) {
            while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

              $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

              $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_fa_number']) && $formVars['r_fa_number'] == $a_r_firearms['fa_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_firearms['fa_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                           . "</td>\n";
              $output .= "</tr>\n";


# associate any ammo with the weapon
              if ($formVars['ammunition'] == 'Yes') {
                $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
                $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
                $q_string .= "from r_ammo ";
                $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
                $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
                $q_string .= "where r_ammo_character = " . $a_r_cyberware['r_ware_character'] . " and r_ammo_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
                $q_string .= "order by ammo_name ";
                $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
                if (mysqli_num_rows($q_r_ammo) > 0) {
                  while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

                    $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

                    $class = "ui-widget-content";

                    print "<tr>\n";
                    print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                                                . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                                                . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                                                . "</td>\n";
                    print "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . "--"                                                                                                    . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                                                                   . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                                                                . "</td>\n";
                    print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                                                . "</td>\n";
                    print "</tr>\n";
                  }
                }
              }
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
  print "<a href=\"cyberware.php" . $display . "\">Reset Display</a>";
  print ", <a href=\"cyberware.php" . $display . $and . "accessory=Yes\">Show Accessories</a>";
  print ", <a href=\"cyberware.php" . $display . $and . "weapons=Yes\">Show Weapons</a>";
  print ", <a href=\"cyberware.php" . $display . $and . "ammunition=Yes\">Show Ammunition</a>";
  print ", <a href=\"cyberware.php" . $display . $and . "weapons=Yes&ammunition=Yes&accessory=Yes\">Show All</a>";
  print "</p>\n";

?>

</div>


<?php

  if ($formVars['opposed'] > -1) {

    print "<div class=\"main\">\n";

    if ($formVars['opposed'] != '') {
      $q_string  = "select grp_name ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['opposed'] . " ";
      $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_groups = mysqli_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">" . $groupname . "Cyberware Information</th>";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberware-help');\">Help</a></th>";
    print "</tr>";
    print "</table>";

    print "<div id=\"cyberware-help\" style=\"display: none\">";

    print "<div class=\"main-help ui-widget-content\">";

    print "<p>Help</p>";

    print "</div>";

    print "</div>";


    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Owner</th>";
    print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
    print "  <th class=\"ui-state-default\">Class</th>";
    print "  <th class=\"ui-state-default\">Cyberware</th>";
    print "  <th class=\"ui-state-default\">Rating</th>";
    print "  <th class=\"ui-state-default\">Essence</th>";
    print "  <th class=\"ui-state-default\">Capacity</th>";
    print "  <th class=\"ui-state-default\">Availability</th>";
    print "  <th class=\"ui-state-default\">Book/Page</th>";
    print "</tr>";

    $q_string  = "select r_ware_id,r_ware_character,class_name,ware_class,ware_name,ware_rating,ware_multiply,ware_essence,";
    $q_string .= "ware_capacity,ware_avail,ware_perm,usr_first,usr_last,runr_name,runr_archetype,grade_name,grade_essence ";
    $q_string .= "from r_cyberware ";
    $q_string .= "left join runners on runners.runr_id = r_cyberware.r_ware_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
    $q_string .= "left join class on class.class_id = cyberware.ware_class ";
    $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
    $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
    $q_string .= "where ver_active = 1 ";
    $q_string .= "order by runr_name,ware_name ";
    $q_r_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_r_cyberware) > 0) {
      while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {

        $display = 'No';

# are we looking at just one group and is the character a member of that group?
        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_cyberware['r_ware_character'] . " ";
          $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_members) > 0) {
            $display = 'Yes';
          }
        } else {
# I'm a Johnson, show me everyone in the group
          if (check_userlevel($db, $AL_Johnson)) {
            $display = 'Yes';
          }
# it's my character so show me no matter what
          if (check_owner($db, $a_r_cyberware['r_ware_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel($db, $AL_Fixer) && check_available($db, $a_r_cyberware['r_ware_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {
          $grade = '';
          if ($a_r_cyberware['grade_essence'] != 1.00) {
            $grade = " (" . $a_r_cyberware['grade_name'] . ")";
          }

          $rating = return_Rating($a_r_cyberware['ware_rate']);

          $capacity = return_Capacity($a_r_cyberware['ware_capacity']);

          $ware_essence = return_Essence($a_r_cyberware['ware_essence'] * $a_r_cyberware['grade_essence']);

          $avail = return_Avail($a_r_cyberware['ware_avail'], $a_r_cyberware['ware_perm']);

          $book = return_Book($a_r_cyberware['ver_book'], $a_r_cyberware['ware_page']);

          print "<tr>";
          print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['usr_first'] . " " . $a_r_cyberware['usr_last']   . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['runr_name'] . " (" . $a_r_cyberware['runr_archetype'] . ")" . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['class_name']                                     . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_cyberware['ware_name'] . $grade                             . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $rating                                                          . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $ware_essence                                                    . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $capacity                                                        . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $avail                                                           . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $book                                                            . "</td>";
          print "</tr>";
        }


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
        if ($formVars['accessory'] == 'Yes') {
          $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $a_r_cyberware['r_ware_character'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by acc_name,acc_rating ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $acc_name = $a_r_accessory['acc_name'];
              if ($a_r_accessory['acc_mount'] != '') {
                $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
              }

              $class = "ui-widget-content";

              $book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              print "<tr>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
              print "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name                                             . "</td>\n";
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
          $q_string .= "where r_ammo_character = " . $a_r_cyberware['r_ware_character'] . " and r_ammo_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by ammo_name ";
          $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

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
              print "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              print "</tr>\n";
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
    print "<a href=\"cyberware.php" . $display . "\">Reset Display</a>";
    print ", <a href=\"cyberware.php" . $display . $and . "ammunition=Yes\">Show Ammunition</a>";
    print ", <a href=\"cyberware.php" . $display . $and . "accessory=Yes\">Show Accessories</a>";
    print ", <a href=\"cyberware.php" . $display . $and . "ammunition=Yes&accessory=Yes\">Show Both</a>";
    print "</p>\n";

    print "</div>\n";

  }
?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
