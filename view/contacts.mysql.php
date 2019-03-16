<?php
# Script: contacts.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "contacts.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Contacts</th>";
  $output .= "</tr>\n";

  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Name</th>\n";
  $output .= "  <th class=\"ui-state-default\">Archetype</th>\n";
  $output .= "  <th class=\"ui-state-default\">Loyalty</th>\n";
  $output .= "  <th class=\"ui-state-default\">Connection</th>\n";
  $output .= "  <th class=\"ui-state-default\">Faction</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select con_name,con_archetype,r_con_loyalty,r_con_connection,r_con_faction,con_book,con_page ";
  $q_string .= "from r_contact ";
  $q_string .= "left join contact on contact.con_id = r_contact.r_con_number ";
  $q_string .= "where r_con_character = " . $formVars['id'] . " ";
  $q_string .= "order by con_archetype,con_name ";
  $q_r_contact = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_contact) > 0) {
    while ($a_r_contact = mysql_fetch_array($q_r_contact)) {
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_contact['con_name']         . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_contact['con_archetype']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_loyalty']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_connection'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_faction']    . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('contacts_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
