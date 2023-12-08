<?php
#session_start(); 
#include('../shadowrun/function.php');

$ref = $_SERVER['HTTP_REFERER'];

// If the user is not logged in, send them to the login page

if (!isset($_SESSION['username'])) {

  header( "Location: /shadowrun/index.php" );
  exit();

}

// Has an error message been passed to login.php?
$error = $_GET['e'];

if ($error == 1) {
  $error = '<div class="error_message">Attention! You must be logged in to view this page.</div>';
}

// Only process if the password reset form has been submitted.

if (isset($_POST['login'])) {

  $username     = $_POST['username']; 
  $oldpassword  = $_POST['oldpassword']; 
  $newpassword1 = $_POST['newpassword1']; 
  $newpassword2 = $_POST['newpassword2']; 

// Check that the user is calling the page from the password reset form and not accessing it directly and redirect back to the password reset form if necessary 

  if (!isset($username) || !isset($oldpassword) || !isset($newpassword1) || !isset($newpassword2)) { 
    header( "Location: ../shadowrun/index.php" ); 
    exit();
  } else {

// Check that the form fields are not empty, and redirect back to the reset password page if they are 
    if (empty($username) || empty($oldpassword) || empty($newpassword1) || empty($newpassword2)) { 
      $error = '<div class="error_message">All fields must be filled in.</div>'; 
    } else { 

// Check that the old password isn't the same as the new password
      if ($oldpassword == $newpassword1) { 
        $error = '<div class="error_message">You cannot reuse passwords.</div>'; 
      } else { 

// Check that the new passwords match
        if ($newpassword1 != $newpassword2) { 
          $error = '<div class="error_message">New Passwords must match.</div>'; 
        } else { 

// Convert the field values to simple variables 

// Add slashes to the username and md5() the password 
          $user = addslashes($_POST['username']); 
          $oldpass = md5($_POST['oldpassword']); 
          $newpass1 = md5($_POST['newpassword1']); 
          $newpass2 = md5($_POST['newpassword2']); 

          $q_string = "select usr_id,usr_first,usr_last from users where usr_name='$user' and usr_passwd='$oldpass'"; 
          $q_users = mysqli_query($db, $q_string);

// Check that at least one row was returned 
          $c_users = mysql_num_rows($q_users); 

          if ($c_users > 0) { 

            $q_string = "update users set usr_passwd = '$newpass1',usr_reset = 0 where usr_name = '$user' and usr_passwd = '$oldpass'";
            $q_users = mysqli_query($db, $q_string);

            logaccess($_SESSION['username'], "pwreset.inc.php", $_SESSION['name'] . " has reset their password.");

//  Successful login code will go here... 

            header( "Location: ".$ref); 
            exit();
          } else { 

// If nothing is returned by the query, unsuccessful login code goes here... 

            $error = '<div class="error_message">Incorrect username or password.</div>'; 
          } 
        }
      }
    }
  }
}

session_start(); 
include('settings.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Mooks Manager</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<META NAME="robots" content="index,follow">

<link rel="stylesheet" href="<?php print $Loginroot; ?>/stylesheet.css" />

</head>
<body>

<div id="header">
    
<div id="title">

<h1>Mooks Login</h1>

</div>

</div>

<div id="main">

<?php

echo $error;

?>

<h2>Password Reset Required</h2>

<form method="POST" action=""> 
<label>Username</label><input type="text" name="username" size="20"> 
<br />
<label>Old Password</label><input type="password" name="oldpassword" size="20"> 
<br />
<label>New Password</label><input type="password" name="newpassword1" size="20"> 
<br />
<label>Re-enter New Password</label><input type="password" name="newpassword2" size="20"> 
<br />
<input type="submit" value="Submit" name="login"> 
</form> 

</div>

<div id="footer">

<a href="<?php print $Siteroot; ?>">Mooks Manager</a>

</div>

</body>
</html>
