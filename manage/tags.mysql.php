<?php
# Script: tags.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "tags.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

      $formVars['group'] = "0";

      $output = "";

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
    }
  }

?>

document.getElementById('tags_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

