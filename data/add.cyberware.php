<?php
# Script: add.cyberware.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.cyberware.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Cyberware</title>

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
function delete_cyberware( p_script_url ) {
  var question;
  var answer;

  question  = "The Cyberware may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Cyberware?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.cyberware.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_cyberware(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&ware_class="    + encode_URI(ac_form.ware_class.value);
  ac_url += "&ware_name="     + encode_URI(ac_form.ware_name.value);
  ac_url += "&ware_rating="   + encode_URI(ac_form.ware_rating.value);
  ac_url += "&ware_multiply=" + ac_form.ware_multiply.checked;
  ac_url += "&ware_essence="  + encode_URI(ac_form.ware_essence.value);
  ac_url += "&ware_capacity=" + encode_URI(ac_form.ware_capacity.value);
  ac_url += "&ware_avail="    + encode_URI(ac_form.ware_avail.value);
  ac_url += "&ware_perm="     + encode_URI(ac_form.ware_perm.value);
  ac_url += "&ware_basetime=" + encode_URI(ac_form.ware_basetime.value);
  ac_url += "&ware_duration=" + encode_URI(ac_form.ware_duration.value);
  ac_url += "&ware_index="    + encode_URI(ac_form.ware_index.value);
  ac_url += "&ware_cost="     + encode_URI(ac_form.ware_cost.value);
  ac_url += "&ware_book="     + encode_URI(ac_form.ware_book.value);
  ac_url += "&ware_page="     + encode_URI(ac_form.ware_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.cyberware.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickCyberware' ).click(function() {
    $( "#dialogCyberware" ).dialog('open');
  });

  $( "#dialogCyberware" ).dialog({
    autoOpen: false,

    modal: true,
    height: 350,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogCyberware" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_cyberware('add.cyberware.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Cyberware",
        click: function() {
          attach_cyberware('add.cyberware.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Cyberware",
        click: function() {
          attach_cyberware('add.cyberware.mysql.php', 0);
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

<form name="cyberware">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Cyberware Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberware-help');">Help</a></th>
</tr>
</table>

<div id="cyberware-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Vehicles Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickCyberware" value="Add New Cyberware"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#earware">Earware</a></li>
  <li><a href="#eyeware">Eyeware</a></li>
  <li><a href="#headware">Headware</a></li>
  <li><a href="#bodyware">Bodyware</a></li>
  <li><a href="#cosmetic">Cosmetic Cyberware Modifications</a></li>
</ul>


<div id="earware">

<span id="earware_table"><?php print wait_Process('Loading Earware...')?></span>

</div>


<div id="eyeware">

<span id="eyeware_table"><?php print wait_Process('Loading Eyeware...')?></span>

</div>


<div id="headware">

<span id="headware_table"><?php print wait_Process('Loading Headware...')?></span>

</div>


<div id="bodyware">

<span id="bodyware_table"><?php print wait_Process('Loading Bodyware...')?></span>

</div>


<div id="cosmetic">

<span id="cosmetic_table"><?php print wait_Process('Loading Cosmetic Cyberware Modifications...')?></span>

</div>


</div>

</div>


<div id="dialogCyberware" title="Cyberware Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.cyberware.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
