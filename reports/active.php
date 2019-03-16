<?php
# Script: active.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "active.php";

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
<title>Active Listing</title>

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

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">" . $groupname . "Active Skill Information</th>\n";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('active-help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"active-help\" style=\"display: none\">\n";

  print "<div class=\"main-help ui-widget-content\">\n";

  print "<p>Help</p>\n";

  print "</div>\n";

  print "</div>\n";


  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>";
  print "  <th class=\"ui-state-default\">Group</th>\n";
  print "  <th class=\"ui-state-default\">Skills</th>\n";

# got a group so list just the members of the group
  if ($formVars['group'] != 0) {
    $q_string  = "select runr_name ";
    $q_string .= "from runners ";
    $q_string .= "left join members on members.mem_runner = runners.runr_id ";
    $q_string .= "where mem_group = " . $formVars['group'] . " ";
    $q_string .= "order by runr_name ";
    $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_runners = mysql_fetch_array($q_runners)) {
      print "  <th class=\"ui-state-default\">" . $a_runners['runr_name'] . "</th>\n";
    }
  } else {
    $q_string  = "select runr_name ";
    $q_string .= "from runners ";
    $q_string .= "order by runr_name ";
    $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_runners = mysql_fetch_array($q_runners)) {
      print "  <th class=\"ui-state-default\">" . $a_runners['runr_name'] . "</th>\n";
    }
  }
  print "  <th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select act_id,act_type,act_name,act_group,att_column,act_default,ver_book,act_page ";
  $q_string .= "from active ";
  $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
  $q_string .= "left join versions on versions.ver_id = active.act_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by act_name ";
  $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_active = mysql_fetch_array($q_active)) {

    $startital = "";
    $endital = "";
    if ($a_active['act_default']) {
      $startital = "<i>";
      $endital = "</i>";
    }

    $book = return_Book($a_active['ver_book'], $a_active['act_page']);

    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $startital . $a_active['act_group'] . $endital . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $startital . $a_active['act_name'] . $endital . "</td>\n";

    if ($formVars['group'] != 0) {
      $q_string  = "select runr_id," . $a_active['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "left join members on members.mem_runner = runners.runr_id ";
      $q_string .= "where mem_group = " . $formVars['group'] . " ";
      $q_string .= "order by runr_name ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_runners = mysql_fetch_array($q_runners)) {

        $q_string  = "select r_act_rank,r_act_specialize ";
        $q_string .= "from r_active ";
        $q_string .= "left join runners on runners.runr_id = r_active.r_act_character ";
        $q_string .= "left join members on members.mem_runner = runners.runr_id ";
        $q_string .= "where mem_group = " . $formVars['group'] . " and r_act_number = " . $a_active['act_id'] . " and r_act_character = " . $a_runners['runr_id'] . " ";
        $q_string .= "order by runr_name ";
        $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_active) > 0) {
          while ($a_r_active = mysql_fetch_array($q_r_active)) {
            $rank = ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank']);
            print "  <td class=\"ui-widget-content delete\">" . $rank;

            if ($a_r_active['r_act_specialize'] != '') {
              print " (" . $a_r_active['r_act_specialize'] . ": " . ($rank + 2) . ")";
            }
            print "</td>\n";
          }
        } else {
          if ($a_active['act_default']) {
            $rank = ($a_runners[$a_active['att_column']] - 1);
            print "  <td class=\"ui-widget-content delete\">" . $startital . $rank . $endital . "</td>\n";
          } else {
            print "  <td class=\"ui-widget-content delete\">" . "&nbsp;" . "</td>\n";
          }
        }
      }
    } else {
      $q_string  = "select runr_id," . $a_active['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "order by runr_name ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_runners = mysql_fetch_array($q_runners)) {

        $q_string  = "select r_act_rank,r_act_specialize ";
        $q_string .= "from r_active ";
        $q_string .= "left join runners on runners.runr_id = r_active.r_act_character ";
        $q_string .= "where r_act_number = " . $a_active['act_id'] . " and r_act_character = " . $a_runners['runr_id'] . " ";
        $q_string .= "order by runr_name ";
        $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_active) > 0) {
          while ($a_r_active = mysql_fetch_array($q_r_active)) {
            $rank = ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank']);
            print "  <td class=\"ui-widget-content delete\">" . $rank;

            if ($a_r_active['r_act_specialize'] != '') {
              print " (" . $a_r_active['r_act_specialize'] . ": " . ($rank + 2) . ")";
            }
            print "</td>\n";
          }
        } else {
          if ($a_active['act_default']) {
            $rank = ($a_runners[$a_active['att_column']] - 1);
            print "  <td class=\"ui-widget-content delete\">" . $startital . $rank . $endital . "</td>\n";
          } else {
            print "  <td class=\"ui-widget-content delete\">" . "&nbsp;" . "</td>\n";
          }
        }
      }
    }
    print "  <td class=\"ui-widget-content delete\">" . $startital . $book . $endital . "</td>\n";
    print "</tr>\n";
  }

  print "</table>\n";
?>

</div>


<?php

  if ($formVars['opposed'] > -1 ) {
    print "<div class=\"main\">\n";

    if ($formVars['opposed'] != 0) {
      $q_string  = "select grp_name ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['opposed'] . " ";
      $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_groups = mysql_fetch_array($q_groups);
      $groupname = $a_groups['grp_name'] . " ";
    } else {
      $groupname = "";
    }

    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\">" . $groupname . "Active Skill Information</th>\n";
    print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('active-help');\">Help</a></th>\n";
    print "</tr>\n";
    print "</table>\n";

    print "<div id=\"active-help\" style=\"display: none\">\n";

    print "<div class=\"main-help ui-widget-content\">\n";

    print "<p>Help</p>\n";

    print "</div>\n";

    print "</div>\n";


    print "<table class=\"ui-styled-table\" width=\"100%\">\n";
    print "<tr>";
    print "  <th class=\"ui-state-default\">Group</th>\n";
    print "  <th class=\"ui-state-default\">Skills</th>\n";

# got a group so list just the members of the group
    if ($formVars['opposed'] != 0) {
      $q_string  = "select runr_name ";
      $q_string .= "from runners ";
      $q_string .= "left join members on members.mem_runner = runners.runr_id ";
      $q_string .= "where mem_group = " . $formVars['opposed'] . " ";
      $q_string .= "order by runr_name ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_runners = mysql_fetch_array($q_runners)) {
        print "  <th class=\"ui-state-default\">" . $a_runners['runr_name'] . "</th>\n";
      }
    } else {
      $q_string  = "select runr_name ";
      $q_string .= "from runners ";
      $q_string .= "order by runr_name ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_runners = mysql_fetch_array($q_runners)) {
        print "  <th class=\"ui-state-default\">" . $a_runners['runr_name'] . "</th>\n";
      }
    }
    print "  <th class=\"ui-state-default\">Book/Page</th>\n";
    print "</tr>\n";

    $q_string  = "select act_id,act_type,act_name,act_group,att_column,act_default,ver_book,act_page ";
    $q_string .= "from active ";
    $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
    $q_string .= "left join versions on versions.ver_id = active.act_book ";
    $q_string .= "where ver_active = 1 ";
    $q_string .= "order by act_name ";
    $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_active = mysql_fetch_array($q_active)) {

      $startital = "";
      $endital = "";
      if ($a_active['act_default']) {
        $startital = "<i>";
        $endital = "</i>";
      }

      $book = return_Book($a_active['ver_book'], $a_active['act_page']);

      print "<tr>\n";
      print "  <td class=\"ui-widget-content\">" . $startital . $a_active['act_group'] . $endital . "</td>\n";
      print "  <td class=\"ui-widget-content\">" . $startital . $a_active['act_name'] . $endital . "</td>\n";

      if ($formVars['opposed'] != 0) {
        $q_string  = "select runr_id," . $a_active['att_column'] . " ";
        $q_string .= "from runners ";
        $q_string .= "left join members on members.mem_runner = runners.runr_id ";
        $q_string .= "where mem_group = " . $formVars['opposed'] . " ";
        $q_string .= "order by runr_name ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {

          $q_string  = "select r_act_rank,r_act_specialize ";
          $q_string .= "from r_active ";
          $q_string .= "left join runners on runners.runr_id = r_active.r_act_character ";
          $q_string .= "left join members on members.mem_runner = runners.runr_id ";
          $q_string .= "where mem_group = " . $formVars['opposed'] . " and r_act_number = " . $a_active['act_id'] . " and r_act_character = " . $a_runners['runr_id'] . " ";
          $q_string .= "order by runr_name ";
          $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_active) > 0) {
            while ($a_r_active = mysql_fetch_array($q_r_active)) {
              $rank = ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank']);
              print "  <td class=\"ui-widget-content delete\">" . $rank;

              if ($a_r_active['r_act_specialize'] != '') {
                print " (" . $a_r_active['r_act_specialize'] . ": " . ($rank + 2) . ")";
              }
              print "</td>\n";
            }
          } else {
            if ($a_active['act_default']) {
              $rank = ($a_runners[$a_active['att_column']] - 1);
              print "  <td class=\"ui-widget-content delete\">" . $startital . $rank . $endital . "</td>\n";
            } else {
              print "  <td class=\"ui-widget-content delete\">" . "&nbsp;" . "</td>\n";
            }
          }
        }
      } else {
        $q_string  = "select runr_id," . $a_active['att_column'] . " ";
        $q_string .= "from runners ";
        $q_string .= "order by runr_name ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {

          $q_string  = "select r_act_rank,r_act_specialize ";
          $q_string .= "from r_active ";
          $q_string .= "left join runners on runners.runr_id = r_active.r_act_character ";
          $q_string .= "where r_act_number = " . $a_active['act_id'] . " and r_act_character = " . $a_runners['runr_id'] . " ";
          $q_string .= "order by runr_name ";
          $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_active) > 0) {
            while ($a_r_active = mysql_fetch_array($q_r_active)) {
              $rank = ($a_runners[$a_active['att_column']] + $a_r_active['r_act_rank']);
              print "  <td class=\"ui-widget-content delete\">" . $rank;
  
              if ($a_r_active['r_act_specialize'] != '') {
                print " (" . $a_r_active['r_act_specialize'] . ": " . ($rank + 2) . ")";
              }
              print "</td>\n";
            }
          } else {
            if ($a_active['act_default']) {
              $rank = ($a_runners[$a_active['att_column']] - 1);
              print "  <td class=\"ui-widget-content delete\">" . $startital . $rank . $endital . "</td>\n";
            } else {
              print "  <td class=\"ui-widget-content delete\">" . "&nbsp;" . "</td>\n";
            }
          }
        }
      }
      print "  <td class=\"ui-widget-content delete\">" . $startital . $book . $endital . "</td>\n";
      print "</tr>\n";
    }
    print "</table>\n";

    print "</div>\n";
  }
?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
