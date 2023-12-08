<?php
# Script: tag.view.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/guest.php');

  $package = "tag.view.php";

  logaccess($formVars['uid'], $package, "Reports tag view.");

  if (isset($_GET['tag'])) {
    $formVars['tag'] = clean($_GET['tag'], 20);
  } else {
    $formVars['tag'] = '';
  }
  if (isset($_GET['type'])) {
    $formVars['type'] = clean($_GET['type'], 10);
  } else {
    $formVars['type'] = 0;
  }

# if help has not been seen yet,
#  if (show_Help($Reportpath . "/" . $package)) {
#    $display = "display: block";
#  } else {
    $display = "display: none";
#  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Tag View: <?php print $formVars['tag']; ?></title>

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

<form name="tags">

<div class="main">

<?php

  $title = '';
# if personal
  if ($formVars['type'] == 0) {
    $tag = "where tag_name = '" . $formVars['tag'] . "' and tag_view = 0 and tag_owner = " . $_SESSION['uid'] . " ";
    $title = "Personal Tag View: " . $formVars['tag'];
  }
# if all
  if ($formVars['type'] == 1) {
    $tag = "where tag_name = '" . $formVars['tag'] . "' and tag_view = 1 ";
    $title = "Public Tag View: " . $formVars['tag'];
  }

  print "<table class=\"ui-styled-table\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">" . $title . "</th>\n";
  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"help\" style=\"" . $display . "\">\n\n";

  print "<div class=\"main-help ui-widget-content\">\n\n";

  print "</div>\n\n";

  print "</div>\n\n";

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Delete</th>\n";
  print "  <th class=\"ui-state-default\">View</th>\n";
  print "  <th class=\"ui-state-default\">Manage</th>\n";
  print "  <th class=\"ui-state-default\">Edit</th>\n";
  print "  <th class=\"ui-state-default\">Owner</th>\n";
  print "  <th class=\"ui-state-default\">Name</th>\n";
  print "  <th class=\"ui-state-default\">Archetype</th>\n";
  print "  <th class=\"ui-state-default\">Metatype</th>\n";
  print "  <th class=\"ui-state-default\">Release</th>\n";
  print "  <th class=\"ui-state-default\">AGI</th>\n";
  print "  <th class=\"ui-state-default\">BOD</th>\n";
  print "  <th class=\"ui-state-default\">REA</th>\n";
  print "  <th class=\"ui-state-default\">STR</th>\n";
  print "  <th class=\"ui-state-default\">CHA</th>\n";
  print "  <th class=\"ui-state-default\">INT</th>\n";
  print "  <th class=\"ui-state-default\">LOG</th>\n";
  print "  <th class=\"ui-state-default\">WIL</th>\n";
  print "  <th class=\"ui-state-default\">EDG</th>\n";
  print "</tr>\n";

  $q_string  = "select runr_id,usr_first,usr_last,runr_name,runr_archetype,meta_name,runr_agility,runr_body, ";
  $q_string .= "runr_reaction,runr_strength,runr_charisma,runr_intuition,runr_logic,runr_willpower, ";
  $q_string .= "runr_totaledge,runr_version ";
  $q_string .= "from runners ";
  $q_string .= "left join users on users.usr_id = runners.runr_owner ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "left join tags on tags.tag_character = runners.runr_id ";
  $q_string .= $tag;
  $q_string .= "order by runr_owner,runr_archetype ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_runners) > 0) {
    while ($a_runners = mysqli_fetch_array($q_runners)) {

      $linkdel     = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_character('mooks.del.php?id="  . $a_runners['runr_id'] . "');\">";
      $viewstart   = "<a href=\"" . $Viewroot   . "/mooks.php?id=" . $a_runners['runr_id'] . "\">";
      $managestart = "<a href=\"" . $Manageroot . "/mooks.php?id=" . $a_runners['runr_id'] . "\">";
      $editstart   = "<a href=\"" . $Editroot   . "/mooks.php?id=" . $a_runners['runr_id'] . "\">";
      $linkend     = "</a>";

      $release = $a_runners['runr_version'];
      if ($a_runners['runr_version'] < 1) {
        $release = "Unset";
      }

      print "<tr>\n";
      print "  <td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                                                       . "</td>\n";
      print "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $viewstart . "View"                                    . $linkend . "</td>\n";
      print "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $managestart . "Manage"                                . $linkend . "</td>\n";
      print "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $editstart . "Edit"                                    . $linkend . "</td>\n";
      print "  <td class=\"ui-widget-content\">"                           . $a_runners['usr_first'] . " " . $a_runners['usr_last']            . "</td>\n";
      print "  <td class=\"ui-widget-content\">"                           . $a_runners['runr_name']                                           . "</td>\n";
      print "  <td class=\"ui-widget-content\">"                           . $a_runners['runr_archetype']                                      . "</td>\n";
      print "  <td class=\"ui-widget-content\">"                           . $a_runners['meta_name']                                           . "</td>\n";
      print "  <td class=\"ui-widget-content\">"                           . $release                                                          . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_agility']                                        . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_body']                                           . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_reaction']                                       . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_strength']                                       . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_charisma']                                       . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_intuition']                                      . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_logic']                                          . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_willpower']                                      . "</td>\n";
      print "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_totaledge']                                      . "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\" colspan=\"14\">No Runners to display.</td>\n";
    print "</tr>\n";
  }

?>
</table>

</div>

</form>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
