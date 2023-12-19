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

  $output = '';

  $q_string  = "select r_jack_id,jack_name,jack_rating,r_jack_data,r_jack_firewall,r_jack_access,r_jack_conmon ";
  $q_string .= "from r_cyberjack ";
  $q_string .= "left join cyberjack on cyberjack.jack_id = r_cyberjack.r_jack_number ";
  $q_string .= "where r_jack_character = " . $formVars['id'] . " ";
  $q_string .= "order by jack_name ";
  $q_r_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberjack) > 0) {

    while ($a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack)) {

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberjack ID: " . $a_r_cyberjack['r_jack_access'] . "</th>";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Cyberjack</th>";
      $output .= "  <th class=\"ui-state-default\">Rating</th>";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "</tr>";

      $jack_rating = return_Rating($a_r_cyberjack['jack_rating']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberjack['jack_name']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $jack_rating                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_data']     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_firewall'] . "</td>";
      $output .= "</tr>";

      $output .= "<tr>\n";
      $matrix_damage = ceil(($a_r_cyberjack['jack_rating'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . "Matrix Damage: (" . $matrix_damage . "): ";
      for ($i = 1; $i <= 12; $i++) {
        if ($matrix_damage >= $i) {
          $checked = '';
          if ($i <= $a_r_cyberjack['r_jack_conmon']) {
            $checked = 'checked=\"true\"';
          }

          $output .= "<input type=\"checkbox\" " . $checked . " id=\"jackcon" . ${i} . "\"  onclick=\"edit_CyberjackCondition(" . ${i} . ", " . $a_r_cyberjack['r_jack_id'] . ", 'cyberjack');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";

      $output .= "</table>";

    }
  } else {
    $output  = "";
  }

  print "document.getElementById('cyberjack_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
