<?php
# Script: add.points.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.points.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Lifestyle Points</title>

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
function delete_points( p_script_url ) {
  var question;
  var answer;

  question  = "The points may be in use by runners. Only delete if you're sure it's unused.\n";
  question += "Delete this point?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.points.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_points(p_script_url, update) {
  var ap_form = document.dialog;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.id.value;

  ap_url += "&point_number="   + encode_URI(ap_form.point_number.value);
  ap_url += "&point_cost="     + encode_URI(ap_form.point_cost.value);
  ap_url += "&point_level="    + encode_URI(ap_form.point_level.value);
  ap_url += "&point_book="     + encode_URI(ap_form.point_book.value);
  ap_url += "&point_page="     + encode_URI(ap_form.point_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.points.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickPoints' ).click(function() {
    $( "#dialogPoints" ).dialog('open');
  });

  $( "#dialogPoints" ).dialog({
    autoOpen: false,

    modal: true,
    height: 225,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogPoints" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_points('add.points.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Points",
        click: function() {
          attach_points('add.points.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Points",
        click: function() {
          attach_points('add.points.mysql.php', 0);
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

<form name="weakness">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Lifestyle Point Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('points-help');">Help</a></th>
</tr>
</table>

<div id="points-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>sprite Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickPoints" value="Add Points"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Lifestyle Points...')?></span>

</div>


<div id="dialogPoints" title="Points">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.points.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
