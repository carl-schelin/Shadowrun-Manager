<?php
# Script: cyberjack.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "cyberjack.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_cyberjack");

      $q_string  = "select jack_name,r_jack_data,r_jack_firewall ";
      $q_string .= "from r_cyberjack ";
      $q_string .= "left join cyberjack on cyberjack.jack_id = r_cyberjack.r_jack_number ";
      $q_string .= "where r_jack_id = " . $formVars['id'];
      $q_r_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack);
      mysqli_free_result($q_r_cyberjack);

      print "document.getElementById('r_jack_item').innerHTML = '" . mysqli_real_escape_string($db, $a_r_cyberjack['jack_name']) . "';\n\n";

      print "document.getElementById('r_jack_data').innerHTML = '"     . mysqli_real_escape_string($db, $a_r_cyberjack['r_jack_data']) . "';\n";
      print "document.getElementById('r_jack_firewall').innerHTML = '" . mysqli_real_escape_string($db, $a_r_cyberjack['r_jack_firewall']) . "';\n";

      print "document.edit.r_jack_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_jack_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
