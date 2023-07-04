<?php
# Script: add.gear.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.gear.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Gear</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel(1)) {
?>
function delete_gear( p_script_url ) {
  var question;
  var answer;

  question  = "The Gear may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Gear?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.gear.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_gear(p_script_url, update) {
  var ab_form = document.dialog;
  var ab_url;

  ab_url  = '?update='   + update;
  ab_url += "&id="       + ab_form.id.value;

  ab_url += "&gear_class="     + encode_URI(ab_form.gear_class.value);
  ab_url += "&gear_name="      + encode_URI(ab_form.gear_name.value);
  ab_url += "&gear_rating="    + encode_URI(ab_form.gear_rating.value);
  ab_url += "&gear_capacity="  + encode_URI(ab_form.gear_capacity.value);
  ab_url += "&gear_avail="     + encode_URI(ab_form.gear_avail.value);
  ab_url += "&gear_perm="      + encode_URI(ab_form.gear_perm.value);
  ab_url += "&gear_basetime="  + encode_URI(ab_form.gear_basetime.value);
  ab_url += "&gear_duration="  + encode_URI(ab_form.gear_duration.value);
  ab_url += "&gear_index="     + encode_URI(ab_form.gear_index.value);
  ab_url += "&gear_cost="      + encode_URI(ab_form.gear_cost.value);
  ab_url += "&gear_book="      + encode_URI(ab_form.gear_book.value);
  ab_url += "&gear_page="      + encode_URI(ab_form.gear_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ab_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.gear.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.gear.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickGear' ).click(function() {
    $( "#dialogGear" ).dialog('open');
  });

  $( "#dialogGear" ).dialog({
    autoOpen: false,

    modal: true,
    height: 325,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogGear" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_gear('add.gear.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Gear",
        click: function() {
          attach_gear('add.gear.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Gear",
        click: function() {
          attach_gear('add.gear.mysql.php', 0);
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

<form name="gear">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Gear Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('gear-help');">Help</a></th>
</tr>
</table>

<div id="gear-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Bioware Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addbio" id="clickGear" value="Add Gear"></td>
</tr>
</table>

</form>

<span id="gear_table"><?php print wait_Process('Loading Gear...')?></span>

</div>

</div>


<div id="dialogGear" title="Gear Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.gear.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
