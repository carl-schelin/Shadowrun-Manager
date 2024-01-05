<?php
# Script: cyberjack.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "cyberjack.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select ver_version ";
  $q_string .= "from versions ";
  $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_versions = mysqli_fetch_array($q_versions);

  $output = "";

  $totalcost = 0;
  $totalessence = 0;
  $q_string  = "select r_jack_id,jack_name,jack_rating,r_jack_data,r_jack_firewall,r_jack_grade,r_jack_access,";
  $q_string .= "jack_essence,jack_avail,jack_perm,ver_book,jack_page,jack_cost,grade_name,grade_cost,grade_avail,grade_essence ";
  $q_string .= "from r_cyberjack ";
  $q_string .= "left join cyberjack on cyberjack.jack_id = r_cyberjack.r_jack_number ";
  $q_string .= "left join grades    on grades.grade_id   = r_cyberjack.r_jack_grade ";
  $q_string .= "left join versions  on versions.ver_id   = cyberjack.jack_book ";
  $q_string .= "where r_jack_character = " . $formVars['id'] . " ";
  $q_string .= "order by jack_rating,ver_version ";
  $q_r_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberjack) > 0) {
    while ($a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack)) {

# in order to get the cyberjack id into the header, we have to add the header here. However there is only one cyberjack (unless someone goes two headed).
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\" colspan=\"8\">Cyberjack ID: " . $a_r_cyberjack['r_jack_access'] . "</th>\n";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Cyberjack</th>";
      $output .= "  <th class=\"ui-state-default\">Rating</th>";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "  <th class=\"ui-state-default\">Essence</th>";
      $output .= "  <th class=\"ui-state-default\">Availability</th>";
      $output .= "  <th class=\"ui-state-default\">Cost</th>";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
      $output .= "</tr>";

      $cyberjack_grade = return_Grade($a_r_cyberjack['grade_essence'], $a_r_cyberjack['grade_name']);

      $cyberjack_rating = return_Rating($a_r_cyberjack['jack_rating']);

      $essencegrade = ($a_r_cyberjack['jack_essence'] * $a_r_cyberjack['grade_essence']);
      $cyberjack_essence = return_Essence($essencegrade);
      $totalessence += $essencegrade;

      $cyberjack_avail = return_Avail(($a_r_cyberjack['grade_avail'] + $a_r_cyberjack['jack_avail']), $a_r_cyberjack['jack_perm']);

      $costgrade = $a_r_cyberjack['jack_cost'] * $a_r_cyberjack['grade_cost'];
      $cyberjack_cost = return_Cost($costgrade);
      $totalcost += $costgrade;

      $cyberjack_book = return_Book($a_r_cyberjack['ver_book'], $a_r_cyberjack['jack_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberjack['jack_name'] . $cyberjack_grade . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_rating                              . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_data']                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_firewall']              . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_essence                             . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_avail                               . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_cost                                . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_book                                . "</td>";
      $output .= "</tr>";

    }

    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"8\">Total cost: " . return_Cost($totalcost) . " Total essence: " . return_Essence($totalessence) . "</td>";
    $output .= "</tr>";
    
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"8\">No Cyberjacks installed</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";

?>

document.getElementById('cyberjacks_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

