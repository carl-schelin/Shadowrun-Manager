<?php
# Script: contact.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "contact.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#contacts\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Contact Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('contact-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"contact-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>";
  $output .= "  <li><strong>Contact</strong> - .</li>";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";

# this has a knowledge and a link to an attribute.
# get the list of knowledge the character knows about
# print name, type, attribute, stat, rank, dice pool


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Name</th>\n";
  $output .= "  <th class=\"ui-state-default\">Archetype</th>\n";
  $output .= "  <th class=\"ui-state-default\">Loyalty</th>\n";
  $output .= "  <th class=\"ui-state-default\">Connection</th>\n";
  $output .= "  <th class=\"ui-state-default\">Faction</th>\n";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select con_name,con_archetype,r_con_loyalty,r_con_connection,r_con_faction,con_book,con_page ";
  $q_string .= "from r_contact ";
  $q_string .= "left join contact on contact.con_id = r_contact.r_con_number ";
  $q_string .= "where r_con_character = " . $formVars['id'] . " ";
  $q_string .= "order by con_archetype,con_name ";
  $q_r_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_contact) > 0) {
    while ($a_r_contact = mysqli_fetch_array($q_r_contact)) {

      $contacts = "--";
      if ($a_r_contact['con_page'] > 0) {
        $contacts = $a_r_contact['con_book'] . "/" . $a_r_contact['con_page'];
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_contact['con_name']         . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_contact['con_archetype']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_loyalty']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_connection'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_contact['r_con_faction']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $contacts                        . "</td>\n";
      $output .= "</tr>\n";
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"6\">No Contacts found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>\n";
?>

document.getElementById('contact_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

