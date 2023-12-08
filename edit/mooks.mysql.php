<?php
# Script: mooks.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  $date = date('Y-m-d');

  if (isset($_SESSION['username'])) {
    $package = "mooks.mysql.php";
    $formVars['id']         = clean($_GET['id'],          10);
    $formVars['update']     = clean($_GET['update'],      10);
    $formVars["runr_name"]  = clean($_GET["runr_name"],   60);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['runr_name'] != 'Blank' && ($formVars['update'] == 0 || $formVars['update'] == 1)) {
        $formVars["runr_owner"]        = clean($_GET["runr_owner"],          10);
        $formVars["runr_aliases"]      = clean($_GET["runr_aliases"],        60);
        $formVars["runr_archetype"]    = clean($_GET["runr_archetype"],      60);
        $formVars["runr_agility"]      = clean($_GET["runr_agility"],        10);
        $formVars["runr_body"]         = clean($_GET["runr_body"],           10);
        $formVars["runr_reaction"]     = clean($_GET["runr_reaction"],       10);
        $formVars["runr_strength"]     = clean($_GET["runr_strength"],       10);
        $formVars["runr_charisma"]     = clean($_GET["runr_charisma"],       10);
        $formVars["runr_intuition"]    = clean($_GET["runr_intuition"],      10);
        $formVars["runr_logic"]        = clean($_GET["runr_logic"],          10);
        $formVars["runr_willpower"]    = clean($_GET["runr_willpower"],      10);
        $formVars["runr_metatype"]     = clean($_GET["runr_metatype"],       10);
        $formVars["runr_essence"]      = clean($_GET["runr_essence"],        10);
        $formVars["runr_totaledge"]    = clean($_GET["runr_totaledge"],      10);
        $formVars["runr_currentedge"]  = clean($_GET["runr_currentedge"],    10);
        $formVars["runr_magic"]        = clean($_GET["runr_magic"],          10);
        $formVars["runr_initiate"]     = clean($_GET["runr_initiate"],       10);
        $formVars["runr_resonance"]    = clean($_GET["runr_resonance"],      10);
        $formVars["runr_age"]          = clean($_GET["runr_age"],            10);
        $formVars["runr_sex"]          = clean($_GET["runr_sex"],            10);
        $formVars["runr_height"]       = clean($_GET["runr_height"],         10);
        $formVars["runr_weight"]       = clean($_GET["runr_weight"],         10);
        $formVars["runr_desc"]         = clean($_GET["runr_desc"],         1000);
        $formVars["runr_sop"]          = clean($_GET["runr_sop"],          1000);
        $formVars["runr_available"]    = clean($_GET["runr_available"],      10);
        $formVars["runr_version"]      = clean($_GET["runr_version"],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['runr_owner'] == '' || $formVars['runr_owner'] == 0) {
          $formVars['runr_owner'] = $_SESSION['uid'];
        }
        if ($formVars['runr_agility'] == '') {
          $formVars['runr_agility'] = 0;
        }
        if ($formVars['runr_body'] == '') {
          $formVars['runr_body'] = 0;
        }
        if ($formVars['runr_reaction'] == '') {
          $formVars['runr_reaction'] = 0;
        }
        if ($formVars['runr_strength'] == '') {
          $formVars['runr_strength'] = 0;
        }
        if ($formVars['runr_charisma'] == '') {
          $formVars['runr_charisma'] = 0;
        }
        if ($formVars['runr_intuition'] == '') {
          $formVars['runr_intuition'] = 0;
        }
        if ($formVars['runr_logic'] == '') {
          $formVars['runr_logic'] = 0;
        }
        if ($formVars['runr_willpower'] == '') {
          $formVars['runr_willpower'] = 0;
        }
        if ($formVars['runr_essence'] == '') {
          $formVars['runr_essence'] = 0;
        }
        if ($formVars['runr_totaledge'] == '') {
          $formVars['runr_totaledge'] = 0;
        }
        if ($formVars['runr_currentedge'] == '') {
          $formVars['runr_currentedge'] = 0;
        }
        if ($formVars['runr_magic'] == '') {
          $formVars['runr_magic'] = 0;
        }
        if ($formVars['runr_initiate'] == '') {
          $formVars['runr_initiate'] = 0;
        }
        if ($formVars['runr_resonance'] == '') {
          $formVars['runr_resonance'] = 0;
        }
        if ($formVars['runr_age'] == '') {
          $formVars['runr_age'] = 0;
        }
        if ($formVars['runr_height'] == '') {
          $formVars['runr_height'] = 0;
        }
        if ($formVars['runr_weight'] == '') {
          $formVars['runr_weight'] = 0;
        }
        if ($formVars['runr_available'] == 'true') {
          $formVars['runr_available'] = 1;
        } else {
          $formVars['runr_available'] = 0;
        }
        if ($formVars['runr_version'] == '') {
          $formVars['runr_version'] = 0;
        }

        $newrunner = $formVars['id'];

        if (strlen($formVars['runr_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "runr_owner           =   " . $formVars['runr_owner']        . "," . 
            "runr_aliases         = \"" . $formVars['runr_aliases']      . "\"," .
            "runr_name            = \"" . $formVars['runr_name']         . "\"," .
            "runr_archetype       = \"" . $formVars['runr_archetype']    . "\"," .
            "runr_agility         =   " . $formVars['runr_agility']      . "," .
            "runr_body            =   " . $formVars['runr_body']         . "," .
            "runr_reaction        =   " . $formVars['runr_reaction']     . "," .
            "runr_strength        =   " . $formVars['runr_strength']     . "," .
            "runr_charisma        =   " . $formVars['runr_charisma']     . "," .
            "runr_intuition       =   " . $formVars['runr_intuition']    . "," .
            "runr_logic           =   " . $formVars['runr_logic']        . "," .
            "runr_willpower       =   " . $formVars['runr_willpower']    . "," .
            "runr_metatype        =   " . $formVars['runr_metatype']     . "," .
            "runr_essence         =   " . $formVars['runr_essence']      . "," .
            "runr_totaledge       =   " . $formVars['runr_totaledge']    . "," .
            "runr_currentedge     =   " . $formVars['runr_currentedge']  . "," . 
            "runr_magic           =   " . $formVars['runr_magic']        . "," .
            "runr_initiate        =   " . $formVars['runr_initiate']     . "," .
            "runr_resonance       =   " . $formVars['runr_resonance']    . "," .
            "runr_age             =   " . $formVars['runr_age']          . "," .
            "runr_sex             =   " . $formVars['runr_sex']          . "," . 
            "runr_height          =   " . $formVars['runr_height']       . "," . 
            "runr_weight          =   " . $formVars['runr_weight']       . "," . 
            "runr_desc            = \"" . $formVars['runr_desc']         . "\"," . 
            "runr_sop             = \"" . $formVars['runr_sop']          . "\"," . 
            "runr_available       =   " . $formVars['runr_available']    . "," . 
            "runr_version         =   " . $formVars['runr_version'];

          if ($formVars['update'] == 1) {
            logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['runr_name']);

            $query = "update members set mem_owner = " . $formVars['runr_owner'] . " where mem_runner = " . $formVars['id'];
            mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

            $query = "update runners set " . $q_string . " where runr_id = " . $formVars['id'];
            mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

            print "alert('Runner updated');\n";
          }

          if ($formVars['update'] == 0) {
            logaccess($_SESSION['username'], $package, "Adding: " . $formVars['runr_name']);

            $query = "insert into runners set runr_id = NULL, " . $q_string;
            $result = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
            $newrunner = last_insert_id();

            print "alert('Runner created');\n";

            print "window.location.href = 'mooks.php?id=" . $newrunner . "';\n";

          }
        }
      }
    }
  }
?>
