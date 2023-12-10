<?php
# Script: tags.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "tags.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $formVars['group'] = "0";

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\">Tags Management</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('tags-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"tags-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Tag Cloud</strong>\n";
  $output .= "  <ul>\n";
  $output .= "    <li><strong>Personal Tag Cloud</strong> - Shows tags that only you can manipulate.</li>\n";
  $output .= "    <li><strong>Public Tag Cloud</strong> - Tags that are viewable by all users of the software.</li>\n";
  $output .= "  </ul></li>\n";
  $output .= "</ul>\n";

  $output .= "</div>";

  $output .= "</div>";

  $output .= "<div class=\"main ui-widget-content\">\n";

  $output .= "<t4>Personal Tag Cloud</t4>\n";

  $output .= "<ul id=\"cloud\">\n";

  $q_string  = "select tag_name ";
  $q_string .= "from tags ";
  $q_string .= "where tag_character = " . $formVars['id'] . " and tag_view = 0 and tag_owner = " . $formVars['uid'] . " ";
  $q_string .= "group by tag_name ";
  $q_tags = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_tags = mysqli_fetch_array($q_tags)) {
    $linkstart = "<a href=\"" . $Reportroot . "/tag.view.php?tag=" . $a_tags['tag_name'] . "\">";
    $linkend   = "</a>";

    $output .= "  <li>" . $linkstart . $a_tags['tag_name'] . $linkend . "</li>\n";
  }

  $output .= "</ul>\n";

  $output .= "</div>\n";

  $output .= "<div class=\"main ui-widget-content\">\n";

  $output .= "<t4>Public Tag Cloud</t4>\n";

  $output .= "<ul id=\"cloud\">\n";

  $q_string  = "select tag_name ";
  $q_string .= "from tags ";
  $q_string .= "where tag_character = " . $formVars['id'] . " and tag_view = 1 ";
  $q_string .= "group by tag_name ";
  $q_tags = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_tags = mysqli_fetch_array($q_tags)) {
    $linkstart = "<a href=\"" . $Reportroot . "/tag.view.php?tag=" . $a_tags['tag_name'] . "\">";
    $linkend   = "</a>";

    $output .= "  <li>" . $linkstart . $a_tags['tag_name'] . $linkend . "</li>\n";
  }

  $output .= "</ul>\n";

  $output .= "</div>\n";

  print "document.getElementById('tags_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

?>
