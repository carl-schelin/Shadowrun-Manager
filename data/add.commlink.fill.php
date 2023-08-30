<?php
# Script: add.commlink.fill.php
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
    $package = "add.commlink.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from commlink");

      $q_string  = "select link_brand,link_model,link_rating,link_data,link_firewall,link_avail,link_perm,";
      $q_string .= "link_cost,link_access,link_book,link_page,link_response,link_signal ";
      $q_string .= "from commlink ";
      $q_string .= "where link_id = " . $formVars['id'];
      $q_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_commlink = mysql_fetch_array($q_commlink);
      mysql_free_result($q_commlink);

      print "document.dialog.link_brand.value = '"    . mysql_real_escape_string($a_commlink['link_brand'])    . "';\n";
      print "document.dialog.link_model.value = '"    . mysql_real_escape_string($a_commlink['link_model'])    . "';\n";
      print "document.dialog.link_rating.value = '"   . mysql_real_escape_string($a_commlink['link_rating'])   . "';\n";
      print "document.dialog.link_response.value = '" . mysql_real_escape_string($a_commlink['link_response']) . "';\n";
      print "document.dialog.link_signal.value = '"   . mysql_real_escape_string($a_commlink['link_signal'])   . "';\n";
      print "document.dialog.link_data.value = '"     . mysql_real_escape_string($a_commlink['link_data'])     . "';\n";
      print "document.dialog.link_firewall.value = '" . mysql_real_escape_string($a_commlink['link_firewall']) . "';\n";
      print "document.dialog.link_access.value = '"   . mysql_real_escape_string($a_commlink['link_access'])   . "';\n";
      print "document.dialog.link_avail.value = '"    . mysql_real_escape_string($a_commlink['link_avail'])    . "';\n";
      print "document.dialog.link_perm.value = '"     . mysql_real_escape_string($a_commlink['link_perm'])     . "';\n";
      print "document.dialog.link_cost.value = '"     . mysql_real_escape_string($a_commlink['link_cost'])     . "';\n";
      print "document.dialog.link_book.value = '"     . mysql_real_escape_string($a_commlink['link_book'])     . "';\n";
      print "document.dialog.link_page.value = '"     . mysql_real_escape_string($a_commlink['link_page'])     . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
