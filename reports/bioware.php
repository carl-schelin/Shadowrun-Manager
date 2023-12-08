<?php
# Script: bioware.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "bioware.php";

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
<title>Bioware Listing</title>

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
    $a_groups = mysqli_fetch_array($q_groups);
    $groupname = $a_groups['grp_name'] . " ";
  } else {
    $groupname = "";
  }

  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">" . $groupname . "Bioware Information</th>";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('bioware-help');\">Help</a></th>";
  print "</tr>";
  print "</table>";

  print "<div id=\"bioware-help\" style=\"display: none\">";

  print "<div class=\"main-help ui-widget-content\">";

  print "<p>Help</p>";

  print "</div>";

  print "</div>";


  print "<table class=\"ui-styled-table\" width=\"100%\">";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Owner</th>";
  print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
  print "  <th class=\"ui-state-default\">Class</th>";
  print "  <th class=\"ui-state-default\">Bioware</th>";
  print "  <th class=\"ui-state-default\">Rating</th>";
  print "  <th class=\"ui-state-default\">Essence</th>";
  print "  <th class=\"ui-state-default\">Book/Page</th>";
  print "</tr>";

  $q_string  = "select r_bio_id,r_bio_character,class_name,bio_class,bio_name,bio_rating,";
  $q_string .= "bio_essence,bio_avail,bio_perm,usr_last,usr_first,runr_name,runr_archetype,";
  $q_string .= "ver_book,bio_page,grade_name,grade_essence ";
  $q_string .= "from r_bioware ";
  $q_string .= "left join runners on runners.runr_id = r_bioware.r_bio_character ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
  $q_string .= "left join class on class.class_id = bioware.bio_class ";
  $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
  $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
  $q_string .= "order by runr_name,bio_class,bio_name,bio_rating,ver_version ";
  $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_bioware) > 0) {
    while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

      $display = "No";

      if ($formVars['group'] > 0) {
        $q_string  = "select mem_id ";
        $q_string .= "from members ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_r_bioware['r_bio_character'] . " ";
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
        if (check_owner($a_r_bioware['r_bio_character'])) {
          $display = 'Yes';
        }
# are we a gm and the character is available for running?
        if (check_userlevel(2) && check_available($a_r_bioware['r_bio_character'])) {
          $display = 'Yes';
        }
      }

      if ($display == 'Yes') {
        $grade = '';
        if ($a_r_bioware['grade_essence'] != 1.00) {
          $grade = " (" . $a_r_bioware['grade_name'] . ")";
        }

        $rating = return_Rating($a_r_bioware['bio_rating']);

        $bio_essence = return_Essence($a_r_bioware['bio_essence'] * $a_r_bioware['grade_essence']);

        $book = return_Book($a_r_bioware['ver_book'], $a_r_bioware['bio_page']);

        print "<tr>";
        print "<td class=\"ui-widget-content\">"        . $a_r_bioware['usr_first'] . " " . $a_r_bioware['usr_last']    . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_bioware['runr_name'] . " (" . $a_r_bioware['runr_archetype'] . ")" . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_bioware['class_name']                                    . "</td>";
        print "<td class=\"ui-widget-content\">"        . $a_r_bioware['bio_name'] . $grade                             . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $rating                                                       . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $bio_essence                                                  . "</td>";
        print "<td class=\"ui-widget-content delete\">" . $book                                                         . "</td>";
        print "</tr>";
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
      $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysqli_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">" . $groupname . "Bioware Information</th>";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('opposed-help');\">Help</a></th>";
    print "</tr>";
    print "</table>";

    print "<div id=\"opposed-help\" style=\"display: none\">";

    print "<div class=\"main-help ui-widget-content\">";

    print "<p>Help</p>";

    print "</div>";

    print "</div>";


    print "<table class=\"ui-styled-table\" width=\"100%\">";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Owner</th>";
    print "  <th class=\"ui-state-default\">Runner (Archetype)</th>";
    print "  <th class=\"ui-state-default\">Class</th>";
    print "  <th class=\"ui-state-default\">Bioware</th>";
    print "  <th class=\"ui-state-default\">Rating</th>";
    print "  <th class=\"ui-state-default\">Essence</th>";
    print "  <th class=\"ui-state-default\">Book/Page</th>";
    print "</tr>";

    $q_string  = "select r_bio_id,r_bio_character,class_name,bio_class,bio_name,bio_rating,";
    $q_string .= "bio_essence,bio_avail,bio_perm,usr_last,usr_first,runr_name,runr_archetype,";
    $q_string .= "ver_book,bio_page,grade_name,grade_essence ";
    $q_string .= "from r_bioware ";
    $q_string .= "left join runners on runners.runr_id = r_bioware.r_bio_character ";
    $q_string .= "left join users on users.usr_id = runners.runr_owner ";
    $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
    $q_string .= "left join class on class.class_id = bioware.bio_class ";
    $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
    $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
    $q_string .= "order by runr_name,bio_class,bio_name,bio_rating,ver_version ";
    $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_bioware) > 0) {
      while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

        $display = "No";

        if ($formVars['opposed'] > 0) {
          $q_string  = "select mem_id ";
          $q_string .= "from members ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and mem_runner = " . $a_r_bioware['r_bio_character'] . " ";
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
          if (check_owner($a_r_bioware['r_bio_character'])) {
            $display = 'Yes';
          }
# are we a gm and the character is available for running?
          if (check_userlevel(2) && check_available($a_r_bioware['r_bio_character'])) {
            $display = 'Yes';
          }
        }

        if ($display == 'Yes') {
          $grade = '';
          if ($a_r_bioware['grade_essence'] != 1.00) {
            $grade = " (" . $a_r_bioware['grade_name'] . ")";
          }

          $rating = return_Rating($a_r_bioware['bio_rating']);

          $bio_essence = return_Essence($a_r_bioware['bio_essence'] * $a_r_bioware['grade_essence']);

          $book = return_Book($a_r_bioware['ver_book'], $a_r_bioware['bio_page']);

          print "<tr>";
          print "<td class=\"ui-widget-content\">"        . $a_r_bioware['usr_first'] . " " . $a_r_bioware['usr_last']    . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_bioware['runr_name'] . " (" . $a_r_bioware['runr_archetype'] . ")" . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_bioware['class_name']                                    . "</td>";
          print "<td class=\"ui-widget-content\">"        . $a_r_bioware['bio_name'] . $grade                             . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $rating                                                       . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $bio_essence                                                  . "</td>";
          print "<td class=\"ui-widget-content delete\">" . $book                                                         . "</td>";
          print "</tr>";
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
