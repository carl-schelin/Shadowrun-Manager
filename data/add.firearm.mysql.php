<?php
# Script: add.firearm.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.firearm.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],               10);
        $formVars['fa_class']         = clean($_GET['fa_class'],         30);
        $formVars['fa_name']          = clean($_GET['fa_name'],         100);
        $formVars['fa_acc']           = clean($_GET['fa_acc'],           10);
        $formVars['fa_damage']        = clean($_GET['fa_damage'],        10);
        $formVars['fa_weight']        = clean($_GET['fa_weight'],        10);
        $formVars['fa_type']          = clean($_GET['fa_type'],          10);
        $formVars['fa_flag']          = clean($_GET['fa_flag'],          10);
        $formVars['fa_conceal']       = clean($_GET['fa_conceal'],       10);
        $formVars['fa_ap']            = clean($_GET['fa_ap'],            10);
        $formVars['fa_mode1']         = clean($_GET['fa_mode1'],         10);
        $formVars['fa_mode2']         = clean($_GET['fa_mode2'],         10);
        $formVars['fa_mode3']         = clean($_GET['fa_mode3'],         10);
        $formVars['fa_ar1']           = clean($_GET['fa_ar1'],           10);
        $formVars['fa_ar2']           = clean($_GET['fa_ar2'],           10);
        $formVars['fa_ar3']           = clean($_GET['fa_ar3'],           10);
        $formVars['fa_ar4']           = clean($_GET['fa_ar4'],           10);
        $formVars['fa_ar5']           = clean($_GET['fa_ar5'],           10);
        $formVars['fa_rc']            = clean($_GET['fa_rc'],            10);
        $formVars['fa_fullrc']        = clean($_GET['fa_fullrc'],        10);
        $formVars['fa_ammo1']         = clean($_GET['fa_ammo1'],         10);
        $formVars['fa_clip1']         = clean($_GET['fa_clip1'],         10);
        $formVars['fa_ammo2']         = clean($_GET['fa_ammo2'],         10);
        $formVars['fa_clip2']         = clean($_GET['fa_clip2'],         10);
        $formVars['fa_avail']         = clean($_GET['fa_avail'],         10);
        $formVars['fa_perm']          = clean($_GET['fa_perm'],          10);
        $formVars['fa_basetime']      = clean($_GET['fa_basetime'],      10);
        $formVars['fa_duration']      = clean($_GET['fa_duration'],      10);
        $formVars['fa_index']         = clean($_GET['fa_index'],         10);
        $formVars['fa_cost']          = clean($_GET['fa_cost'],          10);
        $formVars['fa_book']          = clean($_GET['fa_book'],          10);
        $formVars['fa_page']          = clean($_GET['fa_page'],          10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['fa_acc'] == '') {
          $formVars['fa_acc'] = 0;
        }
        if ($formVars['fa_damage'] == '') {
          $formVars['fa_damage'] = 0;
        }
        if ($formVars['fa_weight'] == '') {
          $formVars['fa_weight'] = 0.00;
        }
        if ($formVars['fa_conceal'] == '') {
          $formVars['fa_conceal'] = 0;
        }
        if ($formVars['fa_ap'] == '') {
          $formVars['fa_ap'] = 0;
        }
        if ($formVars['fa_ar1'] == '') {
          $formVars['fa_ar1'] = 0;
        }
        if ($formVars['fa_ar2'] == '') {
          $formVars['fa_ar2'] = 0;
        }
        if ($formVars['fa_ar3'] == '') {
          $formVars['fa_ar3'] = 0;
        }
        if ($formVars['fa_ar4'] == '') {
          $formVars['fa_ar4'] = 0;
        }
        if ($formVars['fa_ar5'] == '') {
          $formVars['fa_ar5'] = 0;
        }
        if ($formVars['fa_rc'] == '') {
          $formVars['fa_rc'] = 0;
        }
        if ($formVars['fa_fullrc'] == '') {
          $formVars['fa_fullrc'] = 0;
        }
        if ($formVars['fa_ammo1'] == '') {
          $formVars['fa_ammo1'] = 0;
        }
        if ($formVars['fa_ammo2'] == '') {
          $formVars['fa_ammo2'] = 0;
        }
        if ($formVars['fa_avail'] == '') {
          $formVars['fa_avail'] = 0;
        }
        if ($formVars['fa_basetime'] == '') {
          $formVars['fa_basetime'] = 0;
        }
        if ($formVars['fa_index'] == '') {
          $formVars['fa_index'] = 0.00;
        }
        if ($formVars['fa_cost'] == '') {
          $formVars['fa_cost'] = 0;
        }
        if ($formVars['fa_page'] == '') {
          $formVars['fa_page'] = 0;
        }

        if (strlen($formVars['fa_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "fa_class       = \"" . $formVars['fa_class']      . "\"," .
            "fa_name        = \"" . $formVars['fa_name']       . "\"," .
            "fa_acc         =   " . $formVars['fa_acc']        . "," .
            "fa_damage      =   " . $formVars['fa_damage']     . "," .
            "fa_weight      =   " . $formVars['fa_weight']     . "," .
            "fa_type        = \"" . $formVars['fa_type']       . "\"," .
            "fa_flag        = \"" . $formVars['fa_flag']       . "\"," .
            "fa_conceal     =   " . $formVars['fa_conceal']    . "," .
            "fa_ap          =   " . $formVars['fa_ap']         . "," .
            "fa_mode1       = \"" . $formVars['fa_mode1']      . "\"," .
            "fa_mode2       = \"" . $formVars['fa_mode2']      . "\"," .
            "fa_mode3       = \"" . $formVars['fa_mode3']      . "\"," .
            "fa_ar1         =   " . $formVars['fa_ar1']        . "," .
            "fa_ar2         =   " . $formVars['fa_ar2']        . "," .
            "fa_ar3         =   " . $formVars['fa_ar3']        . "," .
            "fa_ar4         =   " . $formVars['fa_ar4']        . "," .
            "fa_ar5         =   " . $formVars['fa_ar5']        . "," .
            "fa_rc          =   " . $formVars['fa_rc']         . "," .
            "fa_fullrc      =   " . $formVars['fa_fullrc']     . "," .
            "fa_ammo1       =   " . $formVars['fa_ammo1']      . "," .
            "fa_clip1       = \"" . $formVars['fa_clip1']      . "\"," .
            "fa_ammo2       =   " . $formVars['fa_ammo2']      . "," .
            "fa_clip2       = \"" . $formVars['fa_clip2']      . "\"," .
            "fa_avail       =   " . $formVars['fa_avail']      . "," .
            "fa_perm        = \"" . $formVars['fa_perm']       . "\"," .
            "fa_basetime    =   " . $formVars['fa_basetime']   . "," .
            "fa_duration    =   " . $formVars['fa_duration']   . "," .
            "fa_index       =   " . $formVars['fa_index']      . "," .
            "fa_cost        =   " . $formVars['fa_cost']       . "," .
            "fa_book        = \"" . $formVars['fa_book']       . "\"," .
            "fa_page        =   " . $formVars['fa_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into firearms set fa_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update firearms set " . $q_string . " where fa_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['fa_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $firearms_list = array("tasers", "hold-outs", "pistols", "mg", "rifles", "shotguns", "special", "launchers", "cyberguns", "error");

      foreach ($firearms_list as &$firearms) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Firearm Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $firearms . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $firearms . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Weapon Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Conceal</th>\n";
        $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">Weight</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP</th>\n";
        $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
        $output .=   "<th class=\"ui-state-default\">RC</th>\n";
        $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Street Index</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Location</th>\n";
        $output .= "</tr>\n";

# this is the default missing items for the error tab
        $orderby = "where class_name != \"Tasers\" "                       . "and " . 
                         "class_name != \"Hold-Outs\" "                    . "and " . 
                         "class_name != \"Light Pistols\" "                . "and " . 
                         "class_name != \"Machine Pistols\" "              . "and " . 
                         "class_name != \"Heavy Pistols\" "                . "and " . 
                         "class_name != \"Submachine Guns\" "              . "and " . 
                         "class_name != \"Light Machine Guns\" "           . "and " . 
                         "class_name != \"Medium Machine Guns\" "          . "and " . 
                         "class_name != \"Heavy Machine Guns\" "           . "and " . 
                         "class_name != \"Machine Guns\" "                 . "and " . 
                         "class_name != \"Assault Rifles\" "               . "and " . 
                         "class_name != \"Sniper Rifles\" "                . "and " . 
                         "class_name != \"Sport Rifles\" "                 . "and " . 
                         "class_name != \"Assault Rifles\" "               . "and " . 
                         "class_name != \"Sniper Rifles\" "                . "and " . 
                         "class_name != \"Sport Rifles\" "                 . "and " . 
                         "class_name != \"Shotguns\" "                     . "and " . 
                         "class_name != \"Special Weapons\" "              . "and " . 
                         "class_name != \"Assault Cannons\" "              . "and " . 
                         "class_name != \"Grenade and Mortar Launchers\" " . "and " . 
                         "class_name != \"Missile Launchers\" "            . "and " . 
                         "class_name != \"Cyberguns\" ";

        if ($firearms == "tasers") {
          $orderby = "where class_name = \"Tasers\" ";
        }
        if ($firearms == "hold-outs") {
          $orderby = "where class_name = \"Hold-Outs\" ";
        }
        if ($firearms == "pistols") {
          $orderby = "where (class_name = \"Light Pistols\" or " . 
                     "class_name = \"Machine Pistols\" or " . 
                     "class_name = \"Heavy Pistols\") ";
        }
        if ($firearms == "mg") {
          $orderby = "where (class_name = \"Submachine Guns\" or " . 
                     "class_name = \"Light Machine Guns\" or " . 
                     "class_name = \"Medium Machine Guns\" or " . 
                     "class_name = \"Heavy Machine Guns\" or " . 
                     "class_name = \"Machine Guns\") ";
        }
        if ($firearms == "rifles") {
          $orderby = "where (class_name = \"Assault Rifles\" or " . 
                     "class_name = \"Sniper Rifles\" or " . 
                     "class_name = \"Sport Rifles\") ";
        }
        if ($firearms == "shotguns") {
          $orderby = "where class_name = \"Shotguns\" ";
        }
        if ($firearms == "special") {
          $orderby = "where class_name = \"Special Weapons\" ";
        }
        if ($firearms == "launchers") {
          $orderby = "where (class_name = \"Assault Cannons\" or " . 
                     "class_name = \"Grenade and Mortar Launchers\" or " . 
                     "class_name = \"Missile Launchers\") ";
        }
        if ($firearms == "cyberguns") {
          $orderby = "where class_name = \"Cyberguns\" ";
        }

        $q_string  = "select fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
        $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,";
        $q_string .= "fa_rc,fa_fullrc,fa_ammo1,fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,";
        $q_string .= "fa_basetime,fa_duration,fa_index,fa_cost,ver_book,fa_page,fa_weight,fa_conceal ";
        $q_string .= "from firearms ";
        $q_string .= "left join class on class.class_id = firearms.fa_class ";
        $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
        $q_string .= $orderby . "and ver_admin = 1 ";
        $q_string .= "order by fa_name,ver_version ";
        $q_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_firearms) > 0) {
          while ($a_firearms = mysqli_fetch_array($q_firearms)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.firearm.fill.php?id="  . $a_firearms['fa_id'] . "');jQuery('#dialogFirearm').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_firearm('add.firearm.del.php?id=" . $a_firearms['fa_id'] . "');\">";
            $linkend = "</a>";

            $fa_accuracy = $a_firearms['fa_acc'];
            if ($a_firearms['fa_acc'] == 0) {
              $fa_accuracy = "--";
            }

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

            $total = 0;
            $q_string  = "select r_fa_id ";
            $q_string .= "from r_firearms ";
            $q_string .= "where r_fa_number = " . $a_firearms['fa_id'] . " ";
            $q_r_farmarms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
            if (mysqli_num_rows($q_r_farmarms) > 0) {
              while ($a_r_farmarms = mysqli_fetch_array($q_r_farmarms)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                        . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_firearms['fa_id']                 . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                               . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_firearms['class_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_firearms['fa_name']    . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_conceal                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_accuracy                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_damage                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_weight                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_ap                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_mode                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_attack                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_rc                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_ammo                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_avail                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_index                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_cost                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $fa_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"18\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $firearms . "_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
      }

      print "document.dialog.fa_name.value = '';\n";
      print "document.dialog.fa_conceal.value = '';\n";
      print "document.dialog.fa_acc.value = '';\n";
      print "document.dialog.fa_damage.value = '';\n";
      print "document.dialog.fa_weight.value = '';\n";
      print "document.dialog.fa_type.value = '';\n";
      print "document.dialog.fa_flag.value = '';\n";
      print "document.dialog.fa_ap.value = '';\n";
      print "document.dialog.fa_mode1.value = '';\n";
      print "document.dialog.fa_mode2.value = '';\n";
      print "document.dialog.fa_mode3.value = '';\n";
      print "document.dialog.fa_ar1.value = '';\n";
      print "document.dialog.fa_ar2.value = '';\n";
      print "document.dialog.fa_ar3.value = '';\n";
      print "document.dialog.fa_ar4.value = '';\n";
      print "document.dialog.fa_ar5.value = '';\n";
      print "document.dialog.fa_rc.value = '';\n";
      print "document.dialog.fa_fullrc.value = '';\n";
      print "document.dialog.fa_ammo1.value = '';\n";
      print "document.dialog.fa_clip1.value = '';\n";
      print "document.dialog.fa_ammo2.value = '';\n";
      print "document.dialog.fa_clip2.value = '';\n";
      print "document.dialog.fa_avail.value = '';\n";
      print "document.dialog.fa_perm.value = '';\n";
      print "document.dialog.fa_basetime.value = '';\n";
      print "document.dialog.fa_duration.value = 0;\n";
      print "document.dialog.fa_index.value = '';\n";
      print "document.dialog.fa_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
