<?php

# set the default timezone
date_default_timezone_set("America/Denver");

# add a space at the end as the company will be inserted into strings.
$Sitecompany		= 'Hobgoblin Consulting Services, LLC ';

# by default, enable debugging in case we missed a server entry. ALL means full on screen debugging
$Sitedebug              = 'ALL';

# Set the environment here so other places in the code can be tested without changing code.
$hostname = php_uname('n');

#############################################################
# Server configurations
#############################################################

if ($hostname == "[hostname]") {
  $Siteenv              = "DEV";
  $Sitedebug            = "NO"; # no error logging
  $Sitedebug            = "ALL"; # log errors to file _and_ to the screen
  $Sitedebug            = "YES"; # log errors just to a file

# Set site specific variables
  $Sitehttp             = "[hostname]";
  $Siteurl              = "http://" . $Sitehttp;

# Changelog location (home directories)
  $Changehome           = "/home";

# Header graphic
  $Siteheader           = "shadowrun.png";

# Path details
  $Sitedir              = "/var/www/html";
  $Siteinstall          = "/manager";

# Who to contact
  $Siteadmins           = ",[siteadmin email]";
  $Sitedev              = "[site dev email]";
  $EmergencyContact     = "[emergency contact email]";

# MySQL specific settings
  $DBtype               = "mysql";
  $DBserver             = "localhost";
  $DBname               = "manager";
  $DBuser               = "[dbuser]";
  $DBpassword           = "[dbpasswd]";
  $DBprefix             = "";
}

# enable debugging

if ( $Sitedebug == 'YES' || $Sitedebug == 'ALL' ) {
# set ini variables to manage error handling
  ini_set('error_reporting', E_ALL | E_STRICT);
  if ($Sitedebug == 'ALL') {
    ini_set('display_errors', 'on');
  } else {
    ini_set('display_errors', 'off');
  }
  ini_set('log_errors', 'On');
  ini_set('error_log', '/var/tmp/manager.log');
}

# site details
$Sitename		= "Shadowrun Character Manager";
$Sitefooter		= "";


# Root directory for the Program
$Sitepath		= $Sitedir . $Siteinstall;
$Siteroot		= $Siteurl . $Siteinstall;

#######
##  Application and Utility specific locations
##  Sitepath is the prefix for OS level files such as include() or fopen()
##  Siteroot is the prefix for URL based files
#######


## Admin Tracking Manager scripts
$Adminpath		= $Sitepath . "/admin";
$Adminroot		= $Siteroot . "/admin";

## Bug Tracking Manager scripts
$Bugpath		= $Sitepath . "/bugs";
$Bugroot		= $Siteroot . "/bugs";

## Issue Tracker scripts
$Issuepath		= $Sitepath . "/issue";
$Issueroot		= $Siteroot . "/issue";

## Feature Tracking Manager scripts
$Featurepath		= $Sitepath . "/features";
$Featureroot		= $Siteroot . "/features";

## Did You Know scripts
$Knowpath		= $Sitepath . "/know";
$Knowroot		= $Siteroot . "/know";

## FAQ Manager scripts
$FAQpath		= $Sitepath . "/faq";
$FAQroot		= $Siteroot . "/faq";


## Database scripts (db modifiers)
$Datapath		= $Sitepath . "/data";
$Dataroot		= $Siteroot . "/data";

## Report path
$Reportpath		= $Sitepath . "/reports";
$Reportroot		= $Siteroot . "/reports";


## Character Edit path
$Editpath		= $Sitepath . "/edit";
$Editroot		= $Siteroot . "/edit";

## Character Manage path
$Managepath		= $Sitepath . "/manage";
$Manageroot		= $Siteroot . "/manage";

## Character View path
$Viewpath		= $Sitepath . "/view";
$Viewroot		= $Siteroot . "/view";


## Login
$Loginpath		= $Sitepath . "/login";
$Loginroot		= $Siteroot . "/login";

## Account Management path
$Userspath		= $Sitepath . "/accounts";
$Usersroot		= $Siteroot . "/accounts";


# disable access to the site and print a maintenance message
$Sitemaintenance	= "0";
$Sitecopyright		= "";

# Access levels
$AL_Johnson		= 1;
$AL_Fixer		= 2;
$AL_Shadowrunner	= 3;
$AL_Chummer		= 3;
$AL_Guest		= 4;

# Set a default theme for users not logged in.
if (!isset($_SESSION['theme'])) {
  $_SESSION['theme']	= 'cupertino';
}

?>
