<?php
  include('settings.php');
  include($Loginpath . '/check.php');
  check_login(4);

  $formVars['uid']      = $_SESSION['uid'];
  $formVars['username'] = $_SESSION['username'];
  $formVars['group']    = $_SESSION['group'];

  include($Sitepath . '/function.php');
?>
