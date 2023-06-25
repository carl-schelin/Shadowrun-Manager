<?php

include('settings.php');

# clean and escape the input data

function clean($input, $maxlength) {
  $input = trim($input);
  $input = substr($input, 0, $maxlength);
  return ($input);
}

# log who did what

function logaccess($user, $source, $detail) {
  include('settings.php');

  $query = "insert into log set " .
    "log_id        = NULL, " .
    "log_user      = \"" . $user   . "\", " .
    "log_source    = \"" . $source . "\", " .
    "log_detail    = \"" . $detail . "\"";

  $insert = mysql_query($query) or die(mysql_error());
}

function check_userlevel( $p_level ) {
  if (isset($_SESSION['username'])) {
    $q_string  = "select usr_level ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $_SESSION['uid'];
    $q_user_level = mysql_query($q_string) or die($q_string . " :" . mysql_error());
    $a_user_level = mysql_fetch_array($q_user_level);

    if ($a_user_level['usr_level'] <= $p_level) {
      return(1);
    } else {
      return(0);
    }
  } else {
    return(0);
  }
}

function last_insert_id() {
  $query = "select last_insert_id()";
  $q_result = mysql_query($query) or die($query . ": " . mysql_error());
  $a_result = mysql_fetch_array($q_result);

  return ($a_result['last_insert_id()']);
}

function generatePassword ($length = 8) {

// start with a blank password
  $password = "";

// define possible characters - any character in this string can be
// picked for use in the password, so if you want to put vowels back in
// or add special characters such as exclamation marks, this is where
// you should do it
  $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

// we refer to the length of $possible a few times, so let's grab it now
  $maxlength = strlen($possible);

// check for length overflow and truncate if necessary
  if ($length > $maxlength) {
    $length = $maxlength;
  }

// set up a counter for how many characters are in the password so far
  $i = 0;

// add random characters to $password until $length is reached
  while ($i < $length) {

// pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, $maxlength-1), 1);

// have we already used this character in $password?
    if (!strstr($password, $char)) {
// no, so it's OK to add it onto the end of whatever we've already got...
      $password .= $char;
// ... and increase the counter by one
      $i++;
    }

  }

// done!
  return $password;

}

function return_Index($p_check, $p_string) {
  $r_index = 0;
  $count = 1;
  $q_table = mysql_query($p_string) or die($p_string . ": " . mysql_error());
  while ($a_table = mysql_fetch_row($q_table)) {
    if ($p_check == $a_table[0]) {
      $r_index = $count;
    }
    $count++;
  }
  return $r_index;
}

function wait_Process($p_string) {
# includeing in order to use path information
  include('settings.php');

#  $randgif = rand(0,1);

  $randgif = 0;

  $output  = "<center>";
  switch ($randgif) {
    case 0: $output .= "<img src=\"" . $Siteroot . "/imgs/3MA_processingbar.gif\">";
            $output .= "<br class=\"iu-widget-content\">" . $p_string;
            break;
    case 1: $output .= "<img src=\"" . $Siteroot . "/imgs/progress_bar.gif\">";
            $output .= "<br class=\"iu-widget-content\">" . $p_string;
            break;
    case 2: $output .= "<img src=\"" . $Siteroot . "/imgs/chasingspheres.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/chasingspheres.gif\">";
            break;
    case 3: $output .= "<img src=\"" . $Siteroot . "/imgs/gears.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/gears.gif\">";
            break;
    case 4: $output .= "<img src=\"" . $Siteroot . "/imgs/recycling.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/recycling.gif\">";
            break;
  }
  $output .= "</center>";

  return $output;
}

# Create a changelog entry for the following database items
# Asset Name
# Manufacturer
# Model
# Serial Number
# State (Active)
# Class
# Location
# clear out any old records for the same system and same table/column when the same one shows up
function changelog( $p_serverid, $p_changed, $p_notes, $p_user, $p_table, $p_column, $p_cleared ) {
  include('settings.php');

# clear previous entries for the same task; so if a server was location changed, only record the last change
# still testing
  $cl_query = "update modified set " .
    "mod_cleared = 1 " .
    "where mod_notes  = \"" . $p_notes  . "\" " .
      "and mod_table  = \"" . $p_table  . "\" " .
      "and mod_column = \"" . $p_column . "\" " .
      "and mod_companyid =   " . $p_serverid;
#  $result = mysql_query($cl_query);

  $cl_query  =
    "mod_companyid    =   " . $p_serverid     . "," .
    "mod_changed      = \"" . $p_changed      . "\"," .
    "mod_notes        = \"" . $p_notes        . "\"," .
    "mod_user         =   " . $p_user         . "," .
    "mod_date         = \"" . date('Y-m-d')   . "\"," .
    "mod_table        = \"" . $p_table        . "\"," .
    "mod_column       = \"" . $p_column       . "\"," .
    "mod_cleared      =   " . $p_cleared;

  $query = "insert into modified set mod_id = null," . $cl_query;

  $result = mysql_query($query);

}

function check_owner( $p_string ) {
  include('settings.php');

  $visible = 0;

  $q_string  = "select runr_owner ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $p_string;
  $q_runners = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  if (mysql_num_rows($q_runners) > 0) {
    $a_runners = mysql_fetch_array($q_runners);

    if ($a_runners['runr_owner'] == $_SESSION['uid']) {
      $visible = 1;
    }
  }

  return $visible;
}

# assumes value passed is kilograms
function return_Pounds( $p_kilograms ) {
  return(number_format(($p_kilograms * 2.20462), 2, '.', ','));
}

# assumes value passed is pounds
function return_Kilograms( $p_pounds ) {
  return(number_format(($p_pounds * .453592), 2, '.', ','));
}

# assumes value passed is inches
function return_Centimeters( $p_inches ) {
  return(number_format(($p_inches * 2.54), 2, '.', ','));
}

# assumes value passed is centimeters
function return_Inches( $p_centimeters ) {
  return(number_format(($p_centimeters * .393701), 2, '.', ','));
}

function return_Feet( $p_inches ) {
  $e_feet = floor($p_inches / 12);
  $e_inches = ($p_inches - ($e_feet * 12));

  $r_feet = $e_feet . "' " . $e_inches . '"';

  return($r_feet);
}

# just move the decimal point over by 2
function return_Meters( $p_centimeters ) {
  return(number_format(($p_centimeters / 100), 2, '.', ','));
}

function check_available( $p_string ) {
  include('settings.php');

  $visible = 0;

  $q_string  = "select runr_available ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $p_string;
  $q_runners = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  if (mysql_num_rows($q_runners) > 0) {
    $a_runners = mysql_fetch_array($q_runners);

    if ($a_runners['runr_available']) {
      $visible = 1;
    }
  }

  return $visible;
}


function members_Available( $p_string ) {
  include('settings.php');

# show if the owner of the group or if the group is marked as visible or you're an admin.
  $q_string  = "select mem_id ";
  $q_string .= "from members ";
  $q_string .= "left join users on users.usr_id = members.mem_owner ";
  $q_string .= "where mem_group = " . $p_string . " and (mem_visible = 1 or mem_owner = " . $_SESSION['uid'] . " or usr_level = " . $AL_Johnson . ") ";
  $q_members = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  if (mysql_num_rows($q_members) > 0) {
    $result = 1;
  } else {
    $result = 0;
  }

  return $result;
}


function groups_Available( $p_string ) {
  include('settings.php');

# show if the owner of the group or if the group is marked as visible or you're an admin.
  $q_string  = "select grp_id ";
  $q_string .= "from groups ";
  $q_string .= "left join users on users.usr_id = groups.grp_owner ";
  $q_string .= "where grp_visible = 1 or grp_owner = " . $_SESSION['uid'] . " or usr_level = " . $AL_Johnson . " ";
  $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  if (mysql_num_rows($q_groups) > 0) {
    $result = 1;
  } else {
    $result = 0;
  }

  return $result;
}


function mooks_Available( $p_string ) {
  include('settings.php');

  $visible   = check_owner($p_string);

# if the owner then just show the character
# otherwise check the user level.
# if admin, just show the character
# if a fixer, if the character is available or a member of your group
# if a shadowrunner and in the same group as you are

  if (!$visible) {
    if (check_userlevel($AL_Johnson)) {
      $visible = 1;
    } else {
      if (check_userlevel($AL_Fixer)) {
# check availability
        $available = check_available($p_string);
        if ($available == 1) {
          $visible = 1;
        } else {
# if the character is not available, fixers can only see if in the same group
          $q_string  = "select grp_id ";
          $q_string .= "from groups ";
          $q_string .= "where grp_owner = " . $_SESSION['uid'] . " ";
          $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          while ($a_groups = mysql_fetch_array($q_groups)) {
# found the groups, now check memberships.
            $q_string  = "select mem_id ";
            $q_string .= "from members ";
# player accepted the invite and is a member of the group
            $q_string .= "where mem_invite = 1 and mem_group = " . $a_groups['grp_id'] . " and mem_runner = " . $p_string . " ";
            $q_members = mysql_query($q_string) or die($q_string . ": " . mysql_error());
            if (mysql_num_rows($q_members) > 0) {
              $visible = 1;
            }
          }
        }
      } else {
        if (check_userlevel($AL_Shadowrunner)) {
# if a member of the same group, show the character information
          $q_string  = "select mem_group ";
          $q_string .= "from members ";
# you have accepted the invite and is a member of the group
          $q_string .= "where mem_invite = 1 and mem_owner = " . $_SESSION['uid'] . " ";
          $q_members = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          if (mysql_num_rows($q_members) > 0) {
            while ($a_members = mysql_fetch_array($q_members)) {
# we have the groups you're a member of. now check p_string's group memberships
              $q_string  = "select mem_id ";
              $q_string .= "from members ";
# you have accepted the invite and is a member of the group
              $q_string .= "where mem_invite = 1 and mem_group = " . $a_members['mem_group'] . " and mem_runner = " . $p_string . " ";
              $q_check = mysql_query($q_string) or die($q_string . ": " . mysql_error());
# yes? then character is visible.
              if (mysql_num_rows($q_check) > 0) {
                $visible = 1;
              }
            }
          }
        }
      }
    }
  }

  return $visible;
}

# if the passed script name for this user isn't here yet, then the user hasn't viewed the help screen yet.
function show_Help( $p_script ) {

  $q_string  = "select help_id ";
  $q_string .= "from help ";
  $q_string .= "where help_user = " . $_SESSION['uid'] . " and help_screen = '" . $p_script . "' ";
  $q_help = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  if (mysql_num_rows($q_help) == 0) {
    $q_string  = "insert ";
    $q_string .= "into help ";
    $q_string .= "set ";
    $q_string .= "help_user = " . $_SESSION['uid'] . ",";
    $q_string .= "help_screen = '" . $p_script . "' ";

    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    return 1;
  } else {
    return 0;
  }
}

function return_Class($p_perm) {
  $r_perm = 'ui-widget-content';
  if ($p_perm == 'R' || $p_perm == 'L') {
    $r_perm = 'ui-state-highlight';
  }
  if ($p_perm == 'F' || $p_perm == 'I') {
    $r_perm = 'ui-state-error';
  }
  return($r_perm);
}

function return_Rating($p_rating) {
  $r_rating = '--';
  if ($p_rating > 0) {
    $r_rating = $p_rating;
  }
  return($r_rating);
}

function return_Essence($p_essence) {
  $r_essence = '--';
  if ($p_essence > 0) {
    $r_essence = number_format($p_essence, 2, '.', ',');
  }
  return($r_essence);
}

function return_Capacity($p_capacity) {
  $r_capacity = '--';
  if ($p_capacity != 0) {
    if ($p_capacity < 0) {
      $r_capacity = "[" . ($p_capacity * -1) . "]";
    } else {
      $r_capacity = $p_capacity;
    }
  }
  return($r_capacity);
}

function return_Avail($p_avail, $p_perm) {
  $r_avail = '--';
  if ($p_avail > 0) {
    $r_avail = $p_avail . $p_perm;
  }
  return($r_avail);
}

function return_Mode($p_mode1, $p_mode2, $p_mode3) {
  $r_mode = $p_mode1;
  if (strlen($p_mode2) > 0) {
    $r_mode .= "/" . $p_mode2;
  }
  if (strlen($p_mode3) > 0) {
    $r_mode .= "/" . $p_mode3;
  }
  return($r_mode);
}

function return_Attack($p_ar1, $p_ar2, $p_ar3, $p_ar4, $p_ar5) {
  $r_attack = $p_ar1 . "/";
  if ($p_ar2 > 0) {
    $r_attack .= $p_ar2;
  } else {
    $r_attack .= '--';
  }
  $r_attack .= "/";
  if ($p_ar3 > 0) {
    $r_attack .= $p_ar3;
  } else {
    $r_attack .= '--';
  }
  $r_attack .= "/";
  if ($p_ar4 > 0) {
    $r_attack .= $p_ar4;
  } else {
    $r_attack .= '--';
  }
  $r_attack .= "/";
  if ($p_ar5 > 0) {
    $r_attack .= $p_ar5;
  } else {
    $r_attack .= '--';
  }
# for systems that don't use this, just return dashes
  if (($p_ar1 + $p_ar2 + $p_ar3 + $p_ar4 + $p_ar5) == 0) {
    $r_attack = '--';
  }
  return($r_attack);
}

function return_Reach($p_reach) {
  $r_reach = '--';
  if ($p_reach > 0) {
    $r_reach = $p_reach;
  }
  return($r_reach);
}

function return_Damage($p_damage, $p_type, $p_flag) {
  $r_damage = $p_damage;
  if (strlen($p_type) > 0) {
    $r_damage .= $p_type;
  }
  if (strlen($p_flag) > 0) {
    $r_damage .= "(" . $p_flag . ")";
  }
  return($r_damage);
}

function return_Strength($p_damage, $p_type, $p_flag, $p_strength) {
  $s_start = "";
  $s_plus  = "";
  $s_end   = "";
  if ($p_strength) {
    $s_start = "(STR";
    $s_plus  = " + ";
    $s_end   = ")";
  }
  $r_damage = $s_start;
  if ($p_damage != 0) {
    $r_damage .= $s_plus . $p_damage;
  }
  $r_damage .= $s_end;

  if (strlen($p_type) > 0) {
    $r_damage .= $p_type;
  }
  if (strlen($p_flag) > 0) {
    $r_damage .= "(" . $p_flag . ")";
  }
  return($r_damage);
}

function return_Recoil($p_recoil, $p_full) {
  $r_recoil = '--';
# sometimes just the full recoil is noted so get full first
  if ($p_full > 0) {
    $r_recoil = "(" . $p_full . ")";
  }
  if ($p_recoil > 0) {
    $r_recoil = $p_recoil;
    if ($p_full > 0) {
      $r_recoil .= "(" . $p_full . ")";
    }
  }
  return($r_recoil);
}

function return_Penetrate($p_penetrate) {
  $r_penetrate = '--';
  if ($p_penetrate > 0) {
    $r_penetrate = "+" . $p_penetrate;
  }
  if ($p_penetrate < 0) {
    $r_penetrate = $p_penetrate;
  }
  return($r_penetrate);
}

function return_Ammo($p_ammo1, $p_clip1, $p_ammo2, $p_clip2) {
  $r_ammo = $p_ammo1;
  if ($p_clip1 != '') {
    $r_ammo .= "(" . $p_clip1 . ")";
  }
  if ($p_ammo2 > 0) {
    $r_ammo .= "/" . $p_ammo2;
    if ($p_clip2 != '') {
      $r_ammo .= "(" . $p_clip2 . ")";
    }
  }
  return($r_ammo);
}

function return_Handling($p_onhand, $p_offhand) {
  if ($p_offhand > 0) {
    $r_handling = $p_onhand . "/" . $p_offhand;
  } else {
    if ($p_onhand == 0) {
      $r_handling = '';
    } else {
      $r_handling = $p_onhand;
    }
  }
  return($r_handling);
}

function return_Speed($p_onspeed, $p_offspeed) {
  if ($p_offspeed > 0) {
    $r_speed = $p_onspeed . "/" . $p_offspeed;
  } else {
    if ($p_onspeed == 0) {
      $r_speed = '';
    } else {
      $r_speed = $p_onspeed;
    }
  }
  return($r_speed);
}

function return_Acceleration($p_onacc, $p_offacc) {
  if ($p_offacc > 0) {
    $r_acceleration = $p_onacc . "/" . $p_offacc;
  } else {
    if ($p_onacc == 0) {
      $r_acceleration = '';
    } else {
      $r_acceleration = $p_onacc;
    }
  }
  return($r_acceleration);
}

function return_Seats($p_onseats, $p_offseats) {
  if ($p_offseats > 0) {
    $r_seats = $p_onseats . "/" . $p_offseats;
  } else {
    if ($p_onseats == 0) {
      $r_seats = '';
    } else {
      $r_seats = $p_onseats;
    }
  }
  return($r_seats);
}

function return_Book($p_book, $p_page) {
  $r_book = "--";
  if ($p_page > 0) {
    $r_book = $p_book . ": " . $p_page;
  }
  return($r_book);
}

function return_Cost($p_cost) {
  $nuyen = '&yen;';
  $r_cost = "No Charge";
  if ($p_cost > 0) {
    $r_cost = number_format($p_cost, 0, '.', ',') . $nuyen;
  }
  return($r_cost);
}

function return_Sprite($p_level, $p_sprite) {
  if ($p_level == 0) {
    $r_sprite = "L";
    if ($p_sprite < 0) {
      $r_sprite = "L" . $p_sprite;
    }
    if ($p_sprite > 0) {
      $r_sprite = "L+" . $p_sprite;
    }
  } else {
    $r_sprite = $p_level + $p_sprite;
  }
  return($r_sprite);
}

function return_Spirit($p_force, $p_spirit) {
  if ($p_force == 0) {
    $r_spirit = "F";
    if ($p_spirit < 0) {
      $r_spirit = "F" . $p_spirit;
    }
    if ($p_spirit > 0) {
      $r_spirit = "F+" . $p_spirit;
    }
  } else {
    $r_spirit = $p_force + $p_spirit;
  }
  return($r_spirit);
}

function return_Mount($p_mount) {
  $r_mount = "--";
  if ($p_mount != '') {
    $r_mount = $p_mount;
  }
  return($r_mount);
}

function return_Drain($p_drain, $p_force) {
  $r_drain = $p_drain;
  if ($p_force == 1) {
    if ($p_drain < 0) {
      $r_drain = "F " . $p_drain;
    }
    if ($p_drain > 0) {
      $r_drain = "F +" . $p_drain;
    }
    if ($p_drain == 0) {
      $r_drain = "F";
    }
  }
  if ($p_force == 2) {
    if ($p_drain < 0) {
      $r_drain = "(F / 2) " . $p_drain;
    }
    if ($p_drain > 0) {
      $r_drain = "(F / 2) +" . $p_drain;
    }
    if ($p_drain == 0) {
      $r_drain = "(F / 2)";
    }
  }
  return($r_drain);
}

?>
