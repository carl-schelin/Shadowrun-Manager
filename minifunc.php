<?php

# the login check.php script set this but when using the minifunc.php script, 
# it doesn't call the check.php login script so the timezone isn't set.
date_default_timezone_set('UTC');

# clean and escape the input data

function clean($input, $maxlength) {
  $input = trim($input);
  $input = substr($input, 0, $maxlength);
  return ($input);
}

function check_userlevel( $p_level ) {
  return 0;
}

function check_grouplevel( $p_level ) {
  return 0;
}

function return_Virtual( $p_string ) {
  $output = 0;

  $q_string  = "select hw_id,mod_virtual ";
  $q_string .= "from hardware ";
  $q_string .= "left join models on models.mod_id = hardware.hw_vendorid ";
  $q_string .= "where hw_companyid = " . $p_string . " and mod_primary = 1 and mod_virtual = 1 ";
  $q_hardware = mysqli_query($db, $q_string) or die($q_string . ": " . mysql_error());

# if there are any rows, then the server is a virtual machine.
  if (mysql_num_rows($q_hardware) > 0) {
    $output = 1;
  }

  return $output;
}

function createNetmaskAddr($bitcount) {
  $netmask = str_split(str_pad(str_pad('', $bitcount, '1'), 32, '0'), 8);
  foreach ($netmask as &$element) $element = bindec($element);
  return join('.', $netmask);
}

/* our simple php ping function */
function ping($host) {
  $sysos = php_uname('s');

  if ($sysos == "Linux") {
    exec(sprintf('/bin/ping -c 1 -w 1 %s', $host), $res, $rval);
  }
  if ($sysos == "SunOS") {
    exec(sprintf('/usr/sbin/ping %s 1', $host), $res, $rval);
  }
  return $rval === 0;
}

function wait_Process($p_string) {
# includeing in order to use path information
  include('settings.php');

  $randgif = rand(0,1);

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

?>
