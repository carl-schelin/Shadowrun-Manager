<?php
# Script: add.users.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.users.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from users");

      $q_string  = "select usr_id,usr_level,usr_disabled,usr_name,usr_first,usr_last,usr_email,usr_phone,";
      $q_string .= "usr_theme,usr_reset ";
      $q_string .= "from users ";
      $q_string .= "where usr_id = " . $formVars['id'];
      $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_users = mysql_fetch_array($q_users);
      mysql_free_result($q_users);

      $disabled = $a_users['usr_disabled'];
      $levels   = return_Index($a_users['usr_level'],    "select lvl_id from levels where lvl_disabled = 0 order by lvl_id");
      $theme    = return_Index($a_users['usr_theme'],    "select theme_id from themes order by theme_title") - 1;

      print "document.user.usr_name.value = '"       . mysql_real_escape_string($a_users['usr_name'])     . "';\n";
      print "document.user.usr_first.value = '"      . mysql_real_escape_string($a_users['usr_first'])    . "';\n";
      print "document.user.usr_last.value = '"       . mysql_real_escape_string($a_users['usr_last'])     . "';\n";
      print "document.user.usr_email.value = '"      . mysql_real_escape_string($a_users['usr_email'])    . "';\n";
      print "document.user.usr_phone.value = '"      . mysql_real_escape_string($a_users['usr_phone'])    . "';\n";

      print "document.user.usr_disabled['" . $disabled . "'].selected = true;\n";
      print "document.user.usr_level['"    . $levels   . "'].selected = true;\n";
      print "document.user.usr_theme['"    . $theme    . "'].selected = true;\n";

      if ($a_users['usr_reset']) {
        print "document.user.usr_reset.checked = true;\n";
      } else {
        print "document.user.usr_reset.checked = false;\n";
      }

      print "document.user.id.value = '" . $formVars['id'] . "'\n";
      print "document.user.update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
