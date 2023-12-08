<?php
include('settings.php');
if (!isset($_SESSION['username'])) {
  session_start(); 
}
include($Sitepath . '/function.php');

$ref = $_SERVER['HTTP_REFERER'];

if (isset($_SESSION['username'])) {

session_start(); 
include('settings.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Mooks Management</title>
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

<div class="error_message">Attention! You are already logged in.</div>

<h2>What to do now?</h2>

Go <a href='javascript:history.go(-1)'>back</a> to the page you were viewing before this.</li>

</div>

<div id="footer"><a href="<?php print $Siteroot; ?>">Mooks Management</a></div>

</body>
</html>
<?php
  exit();
}

// Has an error message been passed to login.php?
$error = '';
if (isset($_GET['e'])) {
  $error = $_GET['e'];
}

if ($error == 1) {
  $error = '<div class="error_message">Attention! You must be logged in to view this page.</div>';
}

// Only process if the login form has been submitted.

if (isset($_POST['login'])) {

  $username = $_POST['username']; 
  $password = $_POST['password']; 

// Check that the user is calling the page from the login form and not accessing it directly 
// and redirect back to the login form if necessary 

  if (!isset($username) || !isset($password)) { 
    header( "Location: " . $Siteroot . "/index.php" ); 
    exit();
  } else {

// Check that the form fields are not empty, and redirect back to the login page if they are 
    if (empty($username) || empty($password)) { 
      header( "Location: " . $Siteroot . "/index.php" );
      exit();
    } else { 

// Convert the field values to simple variables 

// Add slashes to the username and md5() the password 
      $user = addslashes($_POST['username']); 
      $pass = md5($_POST['password']); 

      $q_string  = "select usr_id,usr_first,usr_last,usr_email ";
      $q_string .= "from users ";
      $q_string .= "where usr_name='$user' and usr_passwd='$pass'"; 
      $q_users = mysql_query($q_string);

// Check that at least one row was returned 
      $c_users = mysql_num_rows($q_users); 

      if ($c_users > 0) { 
        while ($a_users = mysqli_fetch_array($q_users)) { 

// Start the session and register variables
          session_start(); 

          $_SESSION["uid"]      = $a_users['usr_id'];
          $_SESSION["name"]     = $a_users['usr_first'] . " " . $a_users['usr_last'];
          $_SESSION["username"] = $user;
          $_SESSION['email']    = $a_users['usr_email'];
          $_SESSION['rand']     = rand(5,1000);
          logaccess($_SESSION['username'], "login.inc.php", $_SESSION['name'] . " has logged in.");

//  Successful login code will go here... 

          $q_string  = "update users set ";
          $q_string .= "usr_lastlogin = \"" . date('Y-m-d H:I:s')     . "\",";
          $q_string .= "usr_ipaddress = \"" . $_SERVER['REMOTE_ADDR'] . "\"";
          $q_string .= "where usr_id = " . $a_users['usr_id'];
          $return = mysql_query($q_string);

          header( "Location: ".$ref); 
          exit();
        } 
      } else { 

// If nothing is returned by the query, unsuccessful login code goes here... 

        $error = '<div class="error_message">Incorrect username or password.</div>'; 
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

<h2>Login</h2>

<form method="POST" action=""> 
<label>Username</label><input type="text" name="username" size="20"> 
<br />
<label>Password</label><input type="password" name="password" size="20"> 
<br />
<input type="submit" value="Submit" name="login"> 
</form> 

<p>Not registered yet? It's easy to do so <a href="<?php print $Loginroot; ?>/sign_up.php">here</a></p>

<p>Don't know what your password is? Click <a href="<?php print $Loginroot; ?>/forgot.php">this link</a> to reset it and have it e-mailed to you.</p>

</div>

<div id="footer"><a href="<?php print $Siteroot; ?>">Mooks Manager</a></div>

</body>
</html>
