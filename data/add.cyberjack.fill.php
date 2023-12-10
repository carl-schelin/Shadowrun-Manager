<?php
# Script: add.cyberjack.fill.php
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
    $package = "add.cyberjack.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from cyberjack");

      $q_string  = "select jack_class,jack_name,jack_rating,jack_data,jack_firewall,jack_matrix,jack_essence,jack_access,jack_avail,";
      $q_string .= "jack_perm,jack_cost,jack_book,jack_page ";
      $q_string .= "from cyberjack ";
      $q_string .= "where jack_id = " . $formVars['id'];
      $q_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_cyberjack = mysqli_fetch_array($q_cyberjack);
      mysqli_free_result($q_cyberjack);

      print "document.dialog.jack_class.value = '"     . mysqli_real_escape_string($db, $a_cyberjack['jack_class'])     . "';\n";
      print "document.dialog.jack_name.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_name'])      . "';\n";
      print "document.dialog.jack_rating.value = '"    . mysqli_real_escape_string($db, $a_cyberjack['jack_rating'])    . "';\n";
      print "document.dialog.jack_essence.value = '"   . mysqli_real_escape_string($db, $a_cyberjack['jack_essence'])   . "';\n";
      print "document.dialog.jack_data.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_data'])      . "';\n";
      print "document.dialog.jack_firewall.value = '"  . mysqli_real_escape_string($db, $a_cyberjack['jack_firewall'])  . "';\n";
      print "document.dialog.jack_matrix.value = '"    . mysqli_real_escape_string($db, $a_cyberjack['jack_matrix'])    . "';\n";
      print "document.dialog.jack_avail.value = '"     . mysqli_real_escape_string($db, $a_cyberjack['jack_avail'])     . "';\n";
      print "document.dialog.jack_access.value = '"    . mysqli_real_escape_string($db, $a_cyberjack['jack_access'])    . "';\n";
      print "document.dialog.jack_perm.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_perm'])      . "';\n";
      print "document.dialog.jack_cost.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_cost'])      . "';\n";
      print "document.dialog.jack_book.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_book'])      . "';\n";
      print "document.dialog.jack_page.value = '"      . mysqli_real_escape_string($db, $a_cyberjack['jack_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
