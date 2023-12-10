<?php
# Script: add.agent.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.agent.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Agents</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_agent( p_script_url ) {
  var question;
  var answer;

  question  = "The Agent will be deleted from all cyberdecks.\n";
  question += "Delete this Agent?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.agent.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_agent(p_script_url, update) {
  var ap_form = document.dialog;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.id.value;

  ap_url += "&agt_name="      + encode_URI(ap_form.agt_name.value);
  ap_url += "&agt_rating="    + encode_URI(ap_form.agt_rating.value);
  ap_url += "&agt_cost="      + encode_URI(ap_form.agt_cost.value);
  ap_url += "&agt_avail="     + encode_URI(ap_form.agt_avail.value);
  ap_url += "&agt_perm="      + encode_URI(ap_form.agt_perm.value);
  ap_url += "&agt_book="      + encode_URI(ap_form.agt_book.value);
  ap_url += "&agt_page="      + encode_URI(ap_form.agt_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.agent.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickAgent' ).click(function() {
    $( "#dialogAgent" ).dialog('open');
  });

  $( "#dialogAgent" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogAgent" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_agent('add.agent.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Agent",
        click: function() {
          attach_agent('add.agent.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Agent",
        click: function() {
          attach_agent('add.agent.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="program">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Agent Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('agent-help');">Help</a></th>
</tr>
</table>

<div id="agent-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Program Form</strong>
  <ul>
    <li><strong>Name</strong> - The name of the Metatype.</li>
    <li><strong>Walk</strong> - The Metatype walking speed.</li>
    <li><strong>Run</strong> - The Metatype run speed.</li>
    <li><strong>Swim</strong> - The Metatype swim speed.</li>
    <li><strong>Book</strong> - Select the book where this table is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addagt" id="clickAgent" value="Add Agent"></td>
</tr>
</table>

</form>

<span id="agent_table"><?php print wait_Process('Loading Agents...')?></span>

</div>


<div id="dialogAgent" title="Computer Agents Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.agent.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
