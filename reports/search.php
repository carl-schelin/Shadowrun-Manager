<?php
# Script: search.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "search.php";

  $formVars['search_by']   = clean($_GET['search_by'],    10);
  $formVars['search_for']  = clean($_GET['search_for'],  255);

  $wait = wait_Process('Please Wait...');

  if ($formVars['search_by'] == 1 || $formVars['search_by'] == 0) {
    print "document.getElementById('mental_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $wait) . "';\n\n";
  }
  if ($formVars['search_by'] == 2 || $formVars['search_by'] == 0) {
    print "document.getElementById('magic_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $wait) . "';\n\n";
  }
  if ($formVars['search_by'] == 3 || $formVars['search_by'] == 0) {
    print "document.getElementById('matrix_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $wait) . "';\n\n";
  }
  if ($formVars['search_by'] == 4 || $formVars['search_by'] == 0) {
    print "document.getElementById('meatspace_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $wait) . "';\n\n";
  }

  if (strlen($formVars['search_for']) > 0) {
    logaccess($db, $formVars['uid'], $package, "Search: " . $formVars['search_for']);

# clean up search_for replacing commas with spaces
    $formVars['search_for'] = str_replace(',', ' ', $formVars['search_for']);
# now replace duplicate spaces with a single space
    $formVars['search_for'] = preg_replace('!\s+!', ' ', $formVars['search_for']);


##########################
##########################
##########################

# mental or all

    if ($formVars['search_by'] == 1 || $formVars['search_by'] == 0) {

      $output  = "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Active Skills</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Group</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attribute</th>\n";
      $output .= "  <th class=\"ui-state-default\">Default</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select act_type,act_name,act_group,att_name,act_default,ver_book,act_page ";
      $q_string .= "from active ";
      $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
      $q_string .= "left join versions on versions.ver_id = active.act_book ";
      $q_string .= "where act_name like '%" . $formVars['search_for'] . "%' and ver_admin = 1 ";
      $q_string .= "order by act_name ";
      $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_active) > 0) {
        while ($a_active = mysqli_fetch_array($q_active)) {

          $active_book = return_Book($a_active['ver_book'], $a_active['act_page']);

          $active_default = "No";
          if ($a_active['act_default']) {
            $active_default = "Yes";
          }

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_active['act_type']  . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_active['act_group'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_active['act_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_active['att_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $active_default        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $active_book           . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Contacts</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Archetype</th>\n";
      $output .= "  <th class=\"ui-state-default\">Character</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select con_name,con_location,con_archetype,con_character,ver_book,con_page,con_owner ";
      $q_string .= "from contact ";
      $q_string .= "left join versions on versions.ver_id = contact.con_book ";
      $q_string .= "where con_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by con_name ";
      $q_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_contact) > 0) {
        while ($a_contact = mysqli_fetch_array($q_contact)) {

          $contact_book = return_Book($a_contact['ver_book'], $a_contact['con_page']);

          if ($a_contact['con_character'] == 0) {
            $a_contact['con_character'] = '';
          }

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_contact['con_name']      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_contact['con_archetype'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_contact['con_character'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $contact_book               . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Knowledge Skills</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Category</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select know_name,s_know_name ";
      $q_string .= "from knowledge ";
      $q_string .= "left join s_knowledge on s_knowledge.s_know_id = knowledge.know_attribute ";
      $q_string .= "where know_name like '%" . $formVars['search_for'] . "%' ";
      $q_string .= "order by know_name ";
      $q_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_knowledge) > 0) {
        while ($a_knowledge = mysqli_fetch_array($q_knowledge)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">" . $a_knowledge['know_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">" . $a_knowledge['s_know_name'] . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Language Skills</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select lang_name,lang_attribute ";
      $q_string .= "from language ";
      $q_string .= "where lang_name like '%" . $formVars['search_for'] . "%' ";
      $q_string .= "order by lang_name ";
      $q_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_language) > 0) {
        while ($a_language = mysqli_fetch_array($q_language)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">" . $a_language['lang_name']     . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Lifestyles</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Lifestyle</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select life_style,life_mincost,life_maxcost,ver_book,life_page ";
      $q_string .= "from lifestyle ";
      $q_string .= "left join versions on versions.ver_id = lifestyle.life_book ";
      $q_string .= "where life_style like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by life_style ";
      $q_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_lifestyle) > 0) {
        while ($a_lifestyle = mysqli_fetch_array($q_lifestyle)) {

          $life_cost = return_Cost($a_lifestyle['life_mincost'], $a_lifestyle['life_maxcost']);

          $life_book = return_Book($a_lifestyle['ver_book'], $a_lifestyle['life_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_lifestyle['life_style'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $life_cost                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $life_book                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Metatypes</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Walk</th>\n";
      $output .= "  <th class=\"ui-state-default\">Run</th>\n";
      $output .= "  <th class=\"ui-state-default\">Swim</th>\n";
      $output .= "  <th class=\"ui-state-default\">Notes</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select meta_name,meta_walk,meta_run,meta_swim,meta_notes,ver_book,meta_page ";
      $q_string .= "from metatypes ";
      $q_string .= "left join versions on versions.ver_id = metatypes.meta_book ";
      $q_string .= "where meta_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by meta_name ";
      $q_metatypes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_metatypes) > 0) {
        while ($a_metatypes = mysqli_fetch_array($q_metatypes)) {

          $meta_book = return_Book($a_metatypes['ver_book'], $a_metatypes['meta_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_metatypes['meta_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_metatypes['meta_walk']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_metatypes['meta_run']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_metatypes['meta_swim']  . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_metatypes['meta_notes'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $meta_book                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Qualities</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Karma Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Positive Quality</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page ";
      $q_string .= "from qualities ";
      $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
      $q_string .= "where qual_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by qual_name ";
      $q_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_qualities) > 0) {
        while ($a_qualities = mysqli_fetch_array($q_qualities)) {

          $qual_positive = 'No';
          if ($a_qualities['qual_value'] > 0) {
            $qual_positive = 'Yes';
          }

          $qual_book = return_Book($a_qualities['ver_book'], $a_qualities['qual_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_qualities['qual_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_qualities['qual_value'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $qual_positive             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_qualities['qual_desc']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $qual_book                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";

      print "document.getElementById('mental_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
    }

# magic or all
    if ($formVars['search_by'] == 2 || $formVars['search_by'] == 0) {

      $output  = "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Adept Powers</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Power Points</th>\n";
      $output .= "  <th class=\"ui-state-default\">Activation</th>\n";
      $output .= "  <th class=\"ui-state-default\">Level</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select adp_name,adp_desc,adp_power,adp_active,adp_level,ver_book,adp_page ";
      $q_string .= "from adept ";
      $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
      $q_string .= "where adp_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by adp_name ";
      $q_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_adept) > 0) {
        while ($a_adept = mysqli_fetch_array($q_adept)) {

          $adept_power = return_Power($a_adept['adp_level']);

          $adept_book = return_Book($a_adept['ver_book'], $a_adept['adp_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_adept['adp_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_adept['adp_desc']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_adept['adp_power']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_adept['adp_active'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $adept_power           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $adept_book            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Mentor Spirits</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">All</th>\n";
      $output .= "  <th class=\"ui-state-default\">Magicians</th>\n";
      $output .= "  <th class=\"ui-state-default\">Adepts</th>\n";
      $output .= "  <th class=\"ui-state-default\">Disadvantages</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select mentor_name,mentor_all,mentor_mage,mentor_adept,mentor_disadvantage,ver_book,mentor_page ";
      $q_string .= "from mentor ";
      $q_string .= "left join versions on versions.ver_id = mentor.mentor_book ";
      $q_string .= "where mentor_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by mentor_name ";
      $q_mentor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_mentor) > 0) {
        while ($a_mentor = mysqli_fetch_array($q_mentor)) {

          $mentor_book = return_Book($a_mentor['ver_book'], $a_mentor['mentor_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_mentor['mentor_name']         . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_mentor['mentor_all']          . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_mentor['mentor_mage']         . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_mentor['mentor_adept']        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_mentor['mentor_disadvantage'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $mentor_book                     . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Metamagics</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select meta_name,meta_description,ver_book,meta_page ";
      $q_string .= "from metamagics ";
      $q_string .= "left join versions on versions.ver_id = metamagics.meta_book ";
      $q_string .= "where meta_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by meta_name ";
      $q_metamagics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_metamagics) > 0) {
        while ($a_metamagics = mysqli_fetch_array($q_metamagics)) {

          $meta_book = return_Book($a_metamagics['ver_book'], $a_metamagics['meta_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_metamagics['meta_name']        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_metamagics['meta_description'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $meta_book                        . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Rituals</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Threshold</th>\n";
      $output .= "  <th class=\"ui-state-default\">Length</th>\n";
      $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select rit_name,rit_anchor,rit_link,rit_minion,rit_spell,rit_spotter,rit_threshold,rit_length,rit_duration,ver_book,rit_page ";
      $q_string .= "from rituals ";
      $q_string .= "left join versions on versions.ver_id = rituals.rit_book ";
      $q_string .= "where rit_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by rit_name ";
      $q_rituals = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_rituals) > 0) {
        while ($a_rituals = mysqli_fetch_array($q_rituals)) {

          $keywords = " (";
          $comma = '';
          if ($a_rituals['rit_anchor']) {
            $keywords .= "Anchored";
            $comma = ', ';
          }
          if ($a_rituals['rit_link']) {
            $keywords .= $comma . "Material Link";
            $comma = ', ';
          }
          if ($a_rituals['rit_minion']) {
            $keywords .= $comma . "Minion";
            $comma = ', ';
          }
          if ($a_rituals['rit_spell']) {
            $keywords .= $comma . "Spell";
            $comma = ', ';
          }
          if ($a_rituals['rit_spotter']) {
            $keywords .= $comma . "Spotter";
          }
          $keywords .= ")";

          $ritual_book = return_Book($a_rituals['ver_book'], $a_rituals['rit_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_rituals['rit_name'] . $keyword . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_rituals['rit_threshold']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_rituals['rit_length']          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_rituals['rit_duration']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ritual_book                      . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Spells</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Test</th>\n";
      $output .= "  <th class=\"ui-state-default\">Range</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
      $output .= "  <th class=\"ui-state-default\">Drain</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select spell_name,spell_group,class_name,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
      $q_string .= "from spells ";
      $q_string .= "left join class on class.class_id = spells.spell_group ";
      $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
      $q_string .= "where spell_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by spell_name ";
      $q_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_spells) > 0) {
        while ($a_spells = mysqli_fetch_array($q_spells)) {

          $spell_drain = return_Drain($a_spells['spell_drain'], $a_spells['spell_force']);

          $spell_book = return_Book($a_spells['ver_book'], $a_spells['spell_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_spells['spell_name']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['class_name']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['spell_type']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['spell_test']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['spell_range']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['spell_damage']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_spells['spell_duration'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spell_drain                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spell_book                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"13\">Spirits</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Body</th>\n";
      $output .= "  <th class=\"ui-state-default\">Agility</th>\n";
      $output .= "  <th class=\"ui-state-default\">Reaction</th>\n";
      $output .= "  <th class=\"ui-state-default\">Strength</th>\n";
      $output .= "  <th class=\"ui-state-default\">Willpower</th>\n";
      $output .= "  <th class=\"ui-state-default\">Logic</th>\n";
      $output .= "  <th class=\"ui-state-default\">Intuition</th>\n";
      $output .= "  <th class=\"ui-state-default\">Charisma</th>\n";
      $output .= "  <th class=\"ui-state-default\">Edge</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence</th>\n";
      $output .= "  <th class=\"ui-state-default\">Magic</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select spirit_name,spirit_body,spirit_agility,spirit_reaction,spirit_strength,";
      $q_string .= "spirit_willpower,spirit_logic,spirit_intuition,spirit_charisma,spirit_edge,";
      $q_string .= "spirit_essence,spirit_magic,spirit_description,ver_book,spirit_page ";
      $q_string .= "from spirits ";
      $q_string .= "left join versions on versions.ver_id = spirits.spirit_book ";
      $q_string .= "where spirit_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by spirit_name ";
      $q_spirits = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_spirits) > 0) {
        while ($a_spirits = mysqli_fetch_array($q_spirits)) {

          $spirit_body      = return_Spirit(0, $a_spirits['spirit_body']);
          $spirit_agility   = return_Spirit(0, $a_spirits['spirit_agility']);
          $spirit_reaction  = return_Spirit(0, $a_spirits['spirit_reaction']);
          $spirit_strength  = return_Spirit(0, $a_spirits['spirit_strength']);
          $spirit_willpower = return_Spirit(0, $a_spirits['spirit_willpower']);
          $spirit_logic     = return_Spirit(0, $a_spirits['spirit_logic']);
          $spirit_intuition = return_Spirit(0, $a_spirits['spirit_intuition']);
          $spirit_charisma  = return_Spirit(0, $a_spirits['spirit_charisma']);
          $spirit_edge      = return_Spirit(0, $a_spirits['spirit_edge']);
          $spirit_essence   = return_Spirit(0, $a_spirits['spirit_essence']);
          $spirit_magic     = return_Spirit(0, $a_spirits['spirit_magic']);

          $spirit_book = return_Book($a_spirits['ver_book'], $a_spirits['spirit_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_spirits['spirit_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_body              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_agility           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_reaction          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_strength          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_willpower         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_logic             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_intuition         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_charisma          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "F/2"                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_essence           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_magic             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $spirit_book              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"7\">Spirit Powers</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Range</th>\n";
      $output .= "  <th class=\"ui-state-default\">Action</th>\n";
      $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pow_name,pow_type,pow_range,pow_action,pow_duration,pow_description,ver_book,pow_page ";
      $q_string .= "from powers ";
      $q_string .= "left join versions on versions.ver_id = powers.pow_book ";
      $q_string .= "where pow_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by pow_name ";
      $q_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_powers) > 0) {
        while ($a_powers = mysqli_fetch_array($q_powers)) {

          $power_book = return_Book($a_powers['ver_book'], $a_powers['pow_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_powers['pow_name']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_powers['pow_type']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_powers['pow_range']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_powers['pow_action']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_powers['pow_duration']    . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_powers['pow_description'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $power_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Traditions</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Tradition</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Combat</th>\n";
      $output .= "  <th class=\"ui-state-default\">Detection</th>\n";
      $output .= "  <th class=\"ui-state-default\">Health</th>\n";
      $output .= "  <th class=\"ui-state-default\">Illusion</th>\n";
      $output .= "  <th class=\"ui-state-default\">Manipulation</th>\n";
      $output .= "  <th class=\"ui-state-default\">Drain</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select trad_name,trad_description,trad_combat,trad_detection,trad_health,trad_illusion,";
      $q_string .= "trad_manipulation,trad_drainleft,trad_drainright,ver_book,trad_page ";
      $q_string .= "from tradition ";
      $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
      $q_string .= "where trad_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by trad_name ";
      $q_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_tradition) > 0) {
        while ($a_tradition = mysqli_fetch_array($q_tradition)) {

          $trad_book = return_Book($a_tradition['ver_book'], $a_tradition['trad_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_name']         . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_description']  . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_combat']       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_detection']    . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_health']       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_illusion']     . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_manipulation'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_tradition['trad_drainleft']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $trad_book                        . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Weaknesses</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select weak_name,weak_description,ver_book,weak_page ";
      $q_string .= "from weakness ";
      $q_string .= "left join versions on versions.ver_id = weakness.weak_book ";
      $q_string .= "where weak_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by weak_name ";
      $q_weakness = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_weakness) > 0) {
        while ($a_weakness = mysqli_fetch_array($q_weakness)) {

          $weakness_book = return_Book($a_weakness['ver_book'], $a_weakness['weak_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_weakness['weak_name']        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_weakness['weak_description'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $weakness_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";

      print "document.getElementById('magic_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
    }

# matrix or all
    if ($formVars['search_by'] == 3 || $formVars['search_by'] == 0) {

      $output  = "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Agents</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select agt_name,agt_rating,agt_cost,agt_avail,agt_perm,ver_book,agt_page ";
      $q_string .= "from agents ";
      $q_string .= "left join versions on versions.ver_id = agents.agt_book ";
      $q_string .= "where agt_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by agt_name ";
      $q_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_agents) > 0) {
        while ($a_agents = mysqli_fetch_array($q_agents)) {


          $agent_avail = return_Avail($a_agents['agt_avail'], $a_agents['agt_perm'], 0, 0);

          $agent_cost = return_Cost($a_agents['agt_cost']);

          $agent_book = return_Book($a_agents['ver_book'], $a_agents['agt_page']);

          $class = return_Class($a_agents['agt_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_agents['agt_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_agents['agt_rating'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $agent_avail            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $agent_cost             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $agent_book             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Command Consoles</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Console</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Programs</th>\n";
      $output .= "  <th class=\"ui-state-default\">Company ID</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select cmd_brand,cmd_model,cmd_rating,cmd_data,cmd_firewall,cmd_programs,";
      $q_string .= "cmd_access,cmd_avail,cmd_perm,cmd_cost,ver_book,cmd_page ";
      $q_string .= "from command ";
      $q_string .= "left join versions on versions.ver_id = command.cmd_book ";
      $q_string .= "where (cmd_model like '%" . $formVars['search_for'] . "%' or cmd_brand like '%" . $formVars['search_for'] . "%') and ver_active = 1 ";
      $q_string .= "order by cmd_model ";
      $q_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_command) > 0) {
        while ($a_command = mysqli_fetch_array($q_command)) {

          $cmd_rating = return_Rating($a_command['cmd_rating']);

          $cmd_avail = return_Avail($a_command['cmd_avail'], $a_command['cmd_perm'], 0, 0);

          $cmd_cost = return_Cost($a_command['cmd_cost']);

          $cmd_book = return_Book($a_command['ver_book'], $a_command['cmd_page']);

          $class = return_Class($a_command['cmd_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_command['cmd_brand'] . " " . $a_command['cmd_model'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $cmd_rating                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_command['cmd_data']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_command['cmd_firewall'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_command['cmd_programs'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_command['cmd_access']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $cmd_avail                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $cmd_cost                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $cmd_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Commlinks</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Commlink</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Response</th>\n";
      $output .= "  <th class=\"ui-state-default\">Signal</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Company ID</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select link_brand,link_model,link_rating,link_response,link_signal,link_data,";
      $q_string .= "link_firewall,link_access,link_avail,link_perm,link_cost,ver_book,link_page ";
      $q_string .= "from commlink ";
      $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
      $q_string .= "where (link_model like '%" . $formVars['search_for'] . "%' or link_brand like '%" . $formVars['search_for'] . "%') and ver_active = 1 ";
      $q_string .= "order by link_model ";
      $q_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_commlink) > 0) {
        while ($a_commlink = mysqli_fetch_array($q_commlink)) {

          $commlink_rating = return_Rating($a_commlink['link_rating']);

          $commlink_avail = return_Avail($a_commlink['link_avail'], $a_commlink['link_perm'], 0, 0);

          $commlink_cost = return_Cost($a_commlink['link_cost']);

          $commlink_book = return_Book($a_commlink['ver_book'], $a_commlink['link_page']);

          $class = return_Class($a_commlink['link_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_commlink['link_brand'] . " " . $a_commlink['link_model'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $commlink_rating             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_commlink['link_response'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_commlink['link_signal']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_commlink['link_data']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_commlink['link_firewall'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_commlink['link_access']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $commlink_avail              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $commlink_cost               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $commlink_book               . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Complex Forms</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Complex Form</th>\n";
      $output .= "  <th class=\"ui-state-default\">Target</th>\n";
      $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
      $output .= "  <th class=\"ui-state-default\">Fading Value</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select form_name,form_target,form_duration,form_level,form_fading,ver_book,form_page ";
      $q_string .= "from complexform ";
      $q_string .= "left join versions on versions.ver_id = complexform.form_book ";
      $q_string .= "where form_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by form_name ";
      $q_complexform = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_complexform) > 0) {
        while ($a_complexform = mysqli_fetch_array($q_complexform)) {

          $form_target = return_Target($a_complexform['form_target']);

          $form_duration = return_Duration($a_complexform['form_duration']);

          $form_fading = return_Complex($a_complexform['form_fading'], $a_complexform['form_level']);

          $form_book = return_Book($a_complexform['ver_book'], $a_complexform['form_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_complexform['form_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_target                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_duration              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_fading                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"20\">Cyberdeck</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Cyberdeck</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">Sleaze</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Persona</th>\n";
      $output .= "  <th class=\"ui-state-default\">Hardening</th>\n";
      $output .= "  <th class=\"ui-state-default\">Memory</th>\n";
      $output .= "  <th class=\"ui-state-default\">Storage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Load</th>\n";
      $output .= "  <th class=\"ui-state-default\">I/O</th>\n";
      $output .= "  <th class=\"ui-state-default\">Response</th>\n";
      $output .= "  <th class=\"ui-state-default\">Programs</th>\n";
      $output .= "  <th class=\"ui-state-default\">Company ID</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select deck_brand,deck_model,deck_rating,deck_attack,deck_sleaze,deck_data,";
      $q_string .= "deck_firewall,deck_persona,deck_hardening,deck_memory,deck_storage,deck_load,";
      $q_string .= "deck_io,deck_response,deck_programs,deck_access,deck_avail,deck_perm,";
      $q_string .= "deck_basetime,deck_duration,deck_index,deck_cost,ver_book,deck_page ";
      $q_string .= "from cyberdeck ";
      $q_string .= "left join versions on versions.ver_id = cyberdeck.deck_book ";
      $q_string .= "where (deck_model like '%" . $formVars['search_for'] . "%' or deck_brand like '%" . $formVars['search_for'] . "%') and ver_active = 1 ";
      $q_string .= "order by deck_model ";
      $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_cyberdeck) > 0) {
        while ($a_cyberdeck = mysqli_fetch_array($q_cyberdeck)) {

          $deck_avail = return_Avail($a_cyberdeck['deck_avail'], $a_cyberdeck['deck_perm'], $a_cyberdeck['deck_basetime'], $a_cyberdeck['deck_duration']);

          $deck_rating = return_Rating($a_cyberdeck['deck_rating']);

          $deck_index = return_StreetIndex($a_cyberdeck['deck_index']);

          $deck_cost = return_Cost($a_cyberdeck['deck_cost']);

          $deck_book = return_Book($a_cyberdeck['ver_book'], $a_cyberdeck['deck_page']);

          $class = return_Class($a_cyberdeck['deck_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_cyberdeck['deck_brand'] . " " . $a_cyberdeck['deck_model'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $deck_rating                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_attack']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_sleaze']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_data']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_firewall']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_persona']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_hardening'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_memory']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_storage']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_load']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_io']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_response']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_programs']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberdeck['deck_access']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $deck_avail                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $deck_index                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $deck_cost                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $deck_book                     . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"20\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberjack</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Matrix Bonus</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence</th>\n";
      $output .= "  <th class=\"ui-state-default\">Company ID</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select jack_class,jack_name,jack_rating,jack_data,jack_firewall,jack_matrix,";
      $q_string .= "jack_access,jack_essence,jack_avail,jack_perm,jack_cost,ver_book,jack_page ";
      $q_string .= "from cyberjack ";
      $q_string .= "left join versions on versions.ver_id = cyberjack.jack_book ";
      $q_string .= "where jack_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by jack_name ";
      $q_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_cyberjack) > 0) {
        while ($a_cyberjack = mysqli_fetch_array($q_cyberjack)) {

          $jack = return_Cyberjack($a_cyberjack['jack_data'], $a_cyberjack['jack_firewall']);

          $jack_rating = return_Rating($a_cyberjack['jack_rating']);

          $jack_essence = return_Essence($a_cyberjack['jack_essence']);

          $jack_avail = return_Avail($a_cyberjack['jack_avail'], $a_cyberjack['jack_perm'], 0, 0);

          $jack_cost = return_Cost($a_cyberjack['jack_cost']);

          $jack_book = return_Book($a_cyberjack['ver_book'], $a_cyberjack['jack_page']);

          $class = return_Class($a_cyberjack['jack_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_cyberjack['jack_name']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $jack_rating                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberjack['jack_data']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberjack['jack_firewall'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberjack['jack_matrix']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $jack_essence                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberjack['jack_access']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $jack_avail                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $jack_cost                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $jack_book                    . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Intrusion Countermeasures</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Defense</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select ic_name,ic_defense,ic_description,ver_book,ic_page ";
      $q_string .= "from ic ";
      $q_string .= "left join versions on versions.ver_id = ic.ic_book ";
      $q_string .= "where ic_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by ic_name ";
      $q_ic = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_ic) > 0) {
        while ($a_ic = mysqli_fetch_array($q_ic)) {

          $ic_book = return_Book($a_ic['ver_book'], $a_ic['ic_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_ic['ic_name']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ic['ic_defense']     . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_ic['ic_description'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ic_book                . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"7\">Matrix Actions</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Level</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">Defense</th>\n";
      $output .= "  <th class=\"ui-state-default\">Access</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select action_name,action_type,action_level,action_attack,action_defense,";
      $q_string .= "action_outsider,action_user,action_admin,ver_book,action_page ";
      $q_string .= "from actions ";
      $q_string .= "left join versions on versions.ver_id = actions.action_book ";
      $q_string .= "where action_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by action_name ";
      $q_actions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_actions) > 0) {
        while ($a_actions = mysqli_fetch_array($q_actions)) {

          $action_type = return_Type($a_actions['action_type']);

          $action_level = return_Level($a_actions['action_level']);

          $action_access = return_Access($a_actions['action_outsider'], $a_actions['action_user'], $a_actions['action_admin']);

          $action_book = return_Book($a_actions['ver_book'], $a_actions['action_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_actions['action_name']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $action_type                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $action_level                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_actions['action_attack']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_actions['action_defense'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $action_access               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $action_book                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Programs</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pgm_name,pgm_type,pgm_desc,pgm_avail,pgm_perm,pgm_cost,ver_book,pgm_page ";
      $q_string .= "from program ";
      $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
      $q_string .= "where pgm_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by pgm_name ";
      $q_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_program) > 0) {
        while ($a_program = mysqli_fetch_array($q_program)) {

          $pgm_type = "Common";
          if ($a_program['pgm_type'] == 1) {
            $pgm_type = "Hacking";
          }
          if ($a_program['pgm_type'] == 2) {
            $pgm_type = "Rigger";
          }
          if ($a_program['pgm_type'] == 3) {
            $pgm_type = "Rigger Hacking";
          }

          $pgm_avail = return_Avail($a_program['pgm_avail'], $a_program['pgm_perm'], 0, 0);

          $pgm_cost = return_Cost($a_program['pgm_cost']);

          $pgm_book = return_Book($a_program['ver_book'], $a_program['pgm_page']);

          $class = return_Class($a_program['pgm_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_program['pgm_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $pgm_type              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_program['pgm_desc'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $pgm_avail             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $pgm_cost              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $pgm_book              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Sprite Powers</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pow_name,pow_description,ver_book,pow_page ";
      $q_string .= "from sprite_powers ";
      $q_string .= "left join versions on versions.ver_id = sprite_powers.pow_book ";
      $q_string .= "where pow_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by pow_name ";
      $q_spirit_powers = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_spirit_powers) > 0) {
        while ($a_spirit_powers = mysqli_fetch_array($q_spirit_powers)) {

          $power_book = return_Book($a_sprite_powers['ver_book'], $a_sprite_powers['pow_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_sprite_powers['pow_name']        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_sprite_powers['pow_description'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $power_book                         . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"8\">Sprites</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">Sleaze</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Initiative</th>\n";
      $output .= "  <th class=\"ui-state-default\">Initiative Dice</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select sprite_name,sprite_attack,sprite_sleaze,sprite_data,sprite_firewall,";
      $q_string .= "sprite_initiative,ver_book,sprite_page ";
      $q_string .= "from sprites ";
      $q_string .= "left join versions on versions.ver_id = sprites.sprite_book ";
      $q_string .= "where sprite_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by sprite_name ";
      $q_sprites = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_sprites) > 0) {
        while ($a_sprites = mysqli_fetch_array($q_sprites)) {

          $sprite_attack   = return_Sprite(0, $a_sprites['sprite_attack']);

          $sprite_sleaze   = return_Sprite(0, $a_sprites['sprite_sleaze']);

          $sprite_data     = return_Sprite(0, $a_sprites['sprite_data']);

          $sprite_firewall = return_Sprite(0, $a_sprites['sprite_firewall']);

          $sprite_book = return_Book($a_sprites['ver_book'], $a_sprites['sprite_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_sprites['sprite_name']                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $sprite_attack                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $sprite_sleaze                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $sprite_data                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $sprite_firewall                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "L+2 + " . $a_sprites['sprite_initiative'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "4d6"                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $sprite_book                               . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";

      print "document.getElementById('matrix_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
    }

# meatspace or all
    if ($formVars['search_by'] == 4 || $formVars['search_by'] == 0) {

      $output  = "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"12\">Accessories</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accessory To</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accessory Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Mount</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Capacity</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select sub_name,class_name,acc_accessory,acc_name,acc_mount,acc_essence,acc_rating,";
      $q_string .= "acc_capacity,acc_avail,acc_perm,acc_basetime,acc_duration,acc_index,acc_cost,";
      $q_string .= "ver_book,acc_page ";
      $q_string .= "from accessory ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join class on class.class_id     = accessory.acc_class ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where acc_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by acc_name ";
      $q_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_accessory) > 0) {
        while ($a_accessory = mysqli_fetch_array($q_accessory)) {

          $itemclass = $a_accessory['class_name'];
          if ($a_accessory['acc_class'] == 0) {
            $itemclass = "Any Subheading";
          }

          $accessory = $a_accessory['acc_accessory'];
          if ($a_accessory['acc_accessory'] == '') {
            $accessory = "Any Item";
          }

          $acc_mount = return_Mount($a_accessory['acc_mount']);

          $acc_essence = return_Essence($a_accessory['acc_essence']);

          $acc_rating = return_Rating($a_accessory['acc_rating']);

          $acc_capacity = return_Capacity($a_accessory['acc_capacity']);

          $acc_avail = return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm'], $a_accessory['acc_basetime'], $a_accessory['acc_duration']);

          $acc_index = return_StreetIndex($a_accessory['acc_index']);

          $acc_cost = return_Cost($a_accessory['acc_cost']);

          $acc_book = return_Book($a_accessory['ver_book'], $a_accessory['acc_page']);

          $class = return_Class($a_accessory['acc_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_accessory['sub_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $itemclass               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $accessory               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_accessory['acc_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_mount               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_essence             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_index               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";




      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"14\">Ammunition</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rounds</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage Modifier</th>\n";
      $output .= "  <th class=\"ui-state-default\">DV Close</th>\n";
      $output .= "  <th class=\"ui-state-default\">DV Near</th>\n";
      $output .= "  <th class=\"ui-state-default\">AP Modifier</th>\n";
      $output .= "  <th class=\"ui-state-default\">Blast</th>\n";
      $output .= "  <th class=\"ui-state-default\">Armor</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_close,";
      $q_string .= "ammo_near,ammo_ap,ammo_blast,ammo_armor,ammo_avail,ammo_perm,ammo_basetime,";
      $q_string .= "ammo_duration,ammo_index,ammo_cost,ver_book,ammo_page ";
      $q_string .= "from ammo ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
      $q_string .= "where ammo_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by ammo_name ";
      $q_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_ammo) > 0) {
        while ($a_ammo = mysqli_fetch_array($q_ammo)) {

          $ammo_rating = return_Rating($a_ammo['ammo_rating']);

          $ammo_ap = return_Penetrate($a_ammo['ammo_ap']);

          $ammo_avail = return_Avail($a_ammo['ammo_avail'], $a_ammo['ammo_perm'], $a_ammo['ammo_basetime'], $a_ammo['ammo_duration']);

          $ammo_index = return_StreetIndex($a_ammo['ammo_index']);

          $ammo_cost = return_Cost($a_ammo['ammo_cost']);

          $ammo_book = return_Book($a_ammo['ver_book'], $a_ammo['ammo_page']);

          $class = return_Class($a_ammo['ammo_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_ammo['class_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_ammo['ammo_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_rounds'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_mod']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_close']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_near']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_blast']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_ammo['ammo_armor']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_index            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_cost             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"14\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Armor</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">B/I</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Capacity</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,arm_name,arm_ballistic,arm_impact,arm_rating,arm_capacity,";
      $q_string .= "arm_avail,arm_perm,arm_basetime,arm_duration,arm_index,arm_cost,ver_book,arm_page ";
      $q_string .= "from armor ";
      $q_string .= "left join class on class.class_id = armor.arm_class ";
      $q_string .= "left join versions on versions.ver_id = armor.arm_book ";
      $q_string .= "where arm_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by arm_name ";
      $q_armor = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_armor) > 0) {
        while ($a_armor = mysqli_fetch_array($q_armor)) {

          $arm_balimp = return_Ballistic($a_armor['arm_ballistic'], $a_armor['arm_impact']);

          $arm_rating = return_Rating($a_armor['arm_rating']);

          $arm_capacity = return_Capacity($a_armor['arm_capacity']);

          $arm_avail = return_Avail($a_armor['arm_avail'], $a_armor['arm_perm'], $a_armor['arm_basetime'], $a_armor['arm_duration']);

          $arm_index = return_StreetIndex($a_armor['arm_index']);

          $arm_cost = return_Cost($a_armor['arm_cost']);

          $arm_book = return_Book($a_armor['ver_book'], $a_armor['arm_page']);

          $class = return_Class($a_armor['arm_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_armor['class_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_armor['arm_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_balimp            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_rating            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_capacity          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_avail             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_index             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_cost              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $arm_book              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Bio/Cyberware Grade</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Grade</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence Multiplier</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability Modifier</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost Multiplier</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select grade_name,grade_essence,grade_avail,grade_cost,ver_book,grade_page ";
      $q_string .= "from grades ";
      $q_string .= "left join versions on versions.ver_id = grades.grade_book ";
      $q_string .= "where grade_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by grade_name ";
      $q_grades = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_grades) > 0) {
        while ($a_grades = mysqli_fetch_array($q_grades)) {

          $grade_avail = return_Avail($a_grades['grade_avail'], "");

          $grade_cost = return_Cost($a_grades['grade_cost']);

          $grade_book = return_Book($a_grades['ver_book'], $a_grades['grade_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_grades['grade_name']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_grades['grade_essence'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $grade_avail               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $grade_cost                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $grade_book                . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"7\">Bioware</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,bio_name,bio_rating,bio_essence,bio_avail,bio_perm,";
      $q_string .= "bio_basetime,bio_duration,bio_index,bio_cost,ver_book,bio_page ";
      $q_string .= "from bioware ";
      $q_string .= "left join class on class.class_id = bioware.bio_class ";
      $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
      $q_string .= "where bio_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by bio_name ";
      $q_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_bioware) > 0) {
        while ($a_bioware = mysqli_fetch_array($q_bioware)) {

          $bio_rating = return_Rating($a_bioware['bio_rating']);

          $bio_essence = return_Essence($a_bioware['bio_essence']);

          $bio_avail = return_Avail($a_bioware['bio_avail'], $a_bioware['bio_perm'], $a_bioware['bio_basetime'], $a_bioware['bio_duration']);

          $bio_index = return_StreetIndex($a_bioware['bio_index']);

          $bio_cost = return_Cost($a_bioware['bio_cost']);

          $bio_book = return_Book($a_bioware['ver_book'], $a_bioware['bio_page']);

          $class = return_Class($a_bioware['bio_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_bioware['bio_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_rating            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_essence           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_avail             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_index             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_cost              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_book              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"9\">Cyberware</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Essence</th>\n";
      $output .= "  <th class=\"ui-state-default\">Capacity</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Legality</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,ware_name,ware_rating,ware_multiply,ware_essence,ware_capacity,";
      $q_string .= "ware_avail,ware_perm,ware_basetime,ware_duration,ware_index,ware_legality,ware_cost,";
      $q_string .= "ver_book,ware_page ";
      $q_string .= "from cyberware ";
      $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
      $q_string .= "left join class on class.class_id = cyberware.ware_class ";
      $q_string .= "where ware_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by ware_name ";
      $q_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_cyberware) > 0) {
        while ($a_cyberware = mysqli_fetch_array($q_cyberware)) {

          $ware_rating = return_Rating($a_cyberware['ware_rating']);

          $ware_essence = return_Essence($a_cyberware['ware_essence']);

          $ware_capacity = return_Capacity($a_cyberware['ware_capacity']);

          $ware_avail = return_Avail($a_cyberware['ware_avail'], $a_cyberware['ware_perm'], $a_cyberware['ware_basetime'], $a_cyberware['ware_duration']);

          $ware_index = return_StreetIndex($a_cyberware['ware_index']);

          $ware_cost = return_Cost($a_cyberware['ware_cost']);

          $ware_book = return_Book($a_cyberware['ver_book'], $a_cyberware['ware_page']);

          $class = return_Class($a_cyberware['ware_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_cyberware['ware_name']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_rating                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_essence                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_capacity                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_avail                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_index                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_cyberware['ware_legality'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_cost                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_book                    . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"15\">Firearms</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Conceal</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accuracy</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Weight</th>\n";
      $output .= "  <th class=\"ui-state-default\">AP</th>\n";
      $output .= "  <th class=\"ui-state-default\">Mode</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">RC</th>\n";
      $output .= "  <th class=\"ui-state-default\">Ammo</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,fa_name,fa_acc,fa_damage,fa_weight,fa_type,fa_flag,fa_conceal,fa_ap,";
      $q_string .= "fa_mode1,fa_mode2,fa_mode3,fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,fa_rc,fa_fullrc,";
      $q_string .= "fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_basetime,fa_duration,";
      $q_string .= "fa_index,fa_cost,ver_book,fa_page ";
      $q_string .= "from firearms ";
      $q_string .= "left join class on class.class_id = firearms.fa_class ";
      $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
      $q_string .= "where fa_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by fa_name ";
      $q_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_firearms) > 0) {
        while ($a_firearms = mysqli_fetch_array($q_firearms)) {

          $fa_mode = return_Mode($a_firearms['fa_mode1'], $a_firearms['fa_mode2'], $a_firearms['fa_mode3']);

          $fa_attack = return_Attack($a_firearms['fa_ar1'], $a_firearms['fa_ar2'], $a_firearms['fa_ar3'], $a_firearms['fa_ar4'], $a_firearms['fa_ar5']);

          $fa_damage = return_Damage($a_firearms['fa_damage'], $a_firearms['fa_type'], $a_firearms['fa_flag']);

          $fa_weight = return_Weight($a_firearms['fa_weight']);

          $fa_conceal = return_Conceal($a_firearms['fa_conceal']);

          $fa_rc = return_Recoil($a_firearms['fa_rc'], $a_firearms['fa_fullrc']);

          $fa_ap = return_Penetrate($a_firearms['fa_ap']);

          $fa_ammo = return_Ammo($a_firearms['fa_ammo1'], $a_firearms['fa_clip1'], $a_firearms['fa_ammo2'], $a_firearms['fa_clip2']);

          $fa_avail = return_Avail($a_firearms['fa_avail'], $a_firearms['fa_perm'], $a_firearms['fa_basetime'], $a_firearms['fa_duration']);

          $fa_index = return_StreetIndex($a_firearms['fa_index']);

          $fa_cost = return_Cost($a_firearms['fa_cost']);

          $fa_book = return_Book($a_firearms['ver_book'], $a_firearms['fa_page']);

          $class = return_Class($a_firearms['fa_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_firearms['class_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_firearms['fa_name']    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_conceal               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_accuracy              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_damage                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_weight                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_ap                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_mode                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_attack                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_rc                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_ammo                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_index                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_cost                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"8\">Gear</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Capacity</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,gear_name,gear_rating,gear_capacity,gear_avail,gear_perm,";
      $q_string .= "gear_basetime,gear_duration,gear_index,gear_cost,ver_book,gear_page ";
      $q_string .= "from gear ";
      $q_string .= "left join class on class.class_id = gear.gear_class ";
      $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
      $q_string .= "where gear_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by gear_name ";
      $q_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_gear) > 0) {
        while ($a_gear = mysqli_fetch_array($q_gear)) {

          $gear_rating = return_Rating($a_gear['gear_rating']);

          $gear_capacity = return_Capacity($a_gear['gear_capacity']);

          $gear_avail = return_Avail($a_gear['gear_avail'], $a_gear['gear_perm'], $a_gear['gear_basetime'], $a_gear['gear_duration']);

          $gear_index = return_StreetIndex($a_gear['gear_index']);

          $gear_cost = return_Cost($a_gear['gear_cost']);

          $gear_book = return_Book($a_gear['ver_book'], $a_gear['gear_page']);

          $class = return_Class($a_gear['gear_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_gear['class_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_gear['gear_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_rating          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_capacity        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_avail           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_index           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_cost            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_book            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"13\">Melee Weapons</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Conceal</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accuracy</th>\n";
      $output .= "  <th class=\"ui-state-default\">Reach</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Weight</th>\n";
      $output .= "  <th class=\"ui-state-default\">AP</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,melee_name,melee_acc,melee_reach,melee_ar1,melee_ar2,melee_ar3,";
      $q_string .= "melee_ar4,melee_ar5,melee_damage,melee_conceal,melee_type,melee_flag,melee_weight,";
      $q_string .= "melee_strength,melee_ap,melee_avail,melee_perm,melee_basetime,melee_duration,";
      $q_string .= "melee_index,melee_cost,ver_book,melee_page ";
      $q_string .= "from melee ";
      $q_string .= "left join class on class.class_id = melee.melee_class ";
      $q_string .= "left join versions on versions.ver_id = melee.melee_book ";
      $q_string .= "where melee_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by melee_name ";
      $q_melee = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_melee) > 0) {
        while ($a_melee = mysqli_fetch_array($q_melee)) {

          $melee_conceal = return_Conceal($a_melee['melee_conceal']);

          $melee_accuracy = return_Accuracy($a_melee['melee_acc']);

          $melee_reach = return_Reach($a_melee['melee_reach']);

          $melee_attack = return_Attack($a_melee['melee_ar1'], $a_melee['melee_ar2'], $a_melee['melee_ar3'], $a_melee['melee_ar4'], $a_melee['melee_ar5']);

          $melee_damage = return_Strength($a_melee['melee_damage'], $a_melee['melee_type'], $a_melee['melee_flag'], $a_melee['melee_strength']);

          $melee_weight = return_Weight($a_melee['melee_weight']);

          $melee_ap = return_Penetrate($a_melee['melee_ap']);

          $melee_avail = return_Avail($a_melee['melee_avail'], $a_melee['melee_perm'], $a_melee['melee_basetime'], $a_melee['melee_duration']);

          $melee_index = return_StreetIndex($a_melee['melee_index']);

          $melee_cost = return_Cost($a_melee['melee_cost']);

          $melee_book = return_Book($a_melee['ver_book'], $a_melee['melee_page']);

          $class = return_Class($a_melee['melee_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_melee['class_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_melee['melee_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_conceal         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_accuracy        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_reach           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_attack          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_damage          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_weight          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_ap              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_avail           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_index           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_cost            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_book            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"11\">Projectile Weapons</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accuracy</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
      $output .= "  <th class=\"ui-state-default\">AP</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,proj_name,proj_rating,proj_acc,proj_ar1,proj_ar2,proj_ar3,proj_ar4,";
      $q_string .= "proj_ar5,proj_damage,proj_type,proj_strength,proj_ap,proj_avail,proj_perm,";
      $q_string .= "proj_basetime,proj_duration,proj_index,proj_cost,ver_book,proj_page ";
      $q_string .= "from projectile ";
      $q_string .= "left join class on class.class_id = projectile.proj_class ";
      $q_string .= "left join versions on versions.ver_id = projectile.proj_book ";
      $q_string .= "where proj_name like '%" . $formVars['search_for'] . "%' and ver_active = 1 ";
      $q_string .= "order by proj_name ";
      $q_projectile = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_projectile) > 0) {
        while ($a_projectile = mysqli_fetch_array($q_projectile)) {

          $proj_rating = return_Rating($a_projectile['proj_rating']);

          $proj_accuracy = return_Accuracy($a_projectile['proj_acc']);

          $proj_attack = return_Attack($a_projectile['proj_ar1'], $a_projectile['proj_ar2'], $a_projectile['proj_ar3'], $a_projectile['proj_ar4'], $a_projectile['proj_ar5']);

          $proj_damage = return_Strength($a_projectile['proj_damage'], $a_projectile['proj_type'], "", $a_projectile['proj_strength']);

          $proj_ap = return_Penetrate($a_projectile['proj_ap']);

          $proj_avail = return_Avail($a_projectile['proj_avail'], $a_projectile['proj_perm'], $a_projectile['proj_basetime'], $a_projectile['proj_duration']);

          $proj_index = return_StreetIndex($a_projectile['proj_index']);

          $proj_cost = return_Cost($a_projectile['proj_cost']);

          $proj_book = return_Book($a_projectile['ver_book'], $a_projectile['proj_page']);

          $class = return_Class($a_projectile['proj_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_projectile['class_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_projectile['proj_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_rating                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_accuracy              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_attack                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_damage                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_ap                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_avail                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_index                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_cost                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $proj_book                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";


      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"23\">Vehicles</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Vehicle</th>\n";
      $output .= "  <th class=\"ui-state-default\">Handling</th>\n";
      $output .= "  <th class=\"ui-state-default\">Interval</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attribute</th>\n";
      $output .= "  <th class=\"ui-state-default\">Speed</th>\n";
      $output .= "  <th class=\"ui-state-default\">kph/mph</th>\n";
      $output .= "  <th class=\"ui-state-default\">Accel</th>\n";
      $output .= "  <th class=\"ui-state-default\">Body</th>\n";
      $output .= "  <th class=\"ui-state-default\">Armor</th>\n";
      $output .= "  <th class=\"ui-state-default\">Pilot</th>\n";
      $output .= "  <th class=\"ui-state-default\">Sensor</th>\n";
      $output .= "  <th class=\"ui-state-default\">Signature</th>\n";
      $output .= "  <th class=\"ui-state-default\">Autonav</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cargo</th>\n";
      $output .= "  <th class=\"ui-state-default\">Load</th>\n";
      $output .= "  <th class=\"ui-state-default\">Hardpoints</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firmpoints</th>\n";
      $output .= "  <th class=\"ui-state-default\">Seats</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Street Index</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_name,veh_type,veh_make,veh_model,veh_onhand,veh_offhand,veh_interval,";
      $q_string .= "veh_rate,veh_onspeed,veh_offspeed,veh_onacc,veh_offacc,veh_pilot,veh_body,veh_armor,";
      $q_string .= "veh_sensor,veh_sig,veh_nav,veh_cargo,veh_load,veh_hardpoints,veh_firmpoints,";
      $q_string .= "veh_onseats,veh_offseats,veh_avail,veh_perm,veh_basetime,veh_duration,veh_index,";
      $q_string .= "veh_cost,ver_book,veh_page ";
      $q_string .= "from vehicles ";
      $q_string .= "left join class on class.class_id = vehicles.veh_class ";
      $q_string .= "left join versions on versions.ver_id = vehicles.veh_book ";
      $q_string .= "where (veh_make like '%" . $formVars['search_for'] . "%' or veh_model like '%" . $formVars['search_for'] . "%') and ver_active = 1 ";
      $q_string .= "order by veh_model ";
      $q_vehicles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_vehicles) > 0) {
        while ($a_vehicles = mysqli_fetch_array($q_vehicles)) {

          $veh_handling = return_Handling($a_vehicles['veh_onhand'], $a_vehicles['veh_offhand']);

          $veh_speed = return_Speed($a_vehicles['veh_onspeed'], $a_vehicles['veh_offspeed']);

          $veh_acceleration = return_Acceleration($a_vehicles['veh_onacc'], $a_vehicles['veh_offacc']);
 
          $kph = 0;
          $mph = 0;
          if ($a_vehicles['ver_version'] == 3.0 || $a_vehicles['ver_version'] == 4.5 || $a_vehicles['ver_version'] == 6.0) {
            $kph = ($a_vehicles['veh_onspeed'] / 3) * 3.6;
            $mph = number_format(($kph / 1.609), 0, '.', ',');
            $kph = number_format($kph, 0, '.', ',');
          }
          if ($a_vehicles['ver_version'] == 1.0 || $a_vehicles['ver_version'] == 2.0 || $a_vehicles['ver_version'] == 5.0) {
            $kph = ($a_vehicles['veh_offspeed'] / 3) * 3.6;
            $mph = number_format(($kph / 1.609), 0, '.', ',');
            $kph = number_format($kph, 0, '.', ',');
          }

          $veh_seats = return_Seats($a_vehicles['veh_onseats'], $a_vehicles['veh_offseats']);

          $veh_avail = return_Avail($a_vehicles['veh_avail'], $a_vehicles['veh_perm'], $a_vehicles['veh_basetime'], $a_vehicles['veh_duration']);

          $veh_index = return_StreetIndex($a_vehicles['veh_index']);

          $veh_cost = return_Cost($a_vehicles['veh_cost']);

          $veh_book = return_Book($a_vehicles['ver_book'], $a_vehicles['veh_page']);

          $class = return_Class($a_vehicles['veh_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_vehicles['veh_type']       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_vehicles['veh_make'] . " " . $a_vehicles['veh_model'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_handling                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_interval']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_rate']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_speed                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $kph . "/" . $mph             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_acceleration             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_body']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_armor']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_pilot']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_sensor']     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_sig']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_nav']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_cargo']      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_load']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_hardpoints'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_vehicles['veh_firmpoints'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_seats                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_avail                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_index                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_cost                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $veh_book                     . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"23\">Search results not found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n\n";

      print "document.getElementById('meatspace_search_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
    }

  }

  print "document.index.search_for.focus();\n";

?>
