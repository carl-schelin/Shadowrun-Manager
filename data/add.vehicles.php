<?php
# Script: add.vehicles.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.vehicles.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Vehicles</title>

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
function delete_vehicles( p_script_url ) {
  var question;
  var answer;

  question  = "The Vehicle may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Vehicle?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.vehicles.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_vehicles(p_script_url, update) {
  var av_form = document.dialog;
  var av_url;

  av_url  = '?update='   + update;
  av_url += "&id="       + av_form.id.value;

  av_url += "&veh_class="        + encode_URI(av_form.veh_class.value);
  av_url += "&veh_type="         + encode_URI(av_form.veh_type.value);
  av_url += "&veh_make="         + encode_URI(av_form.veh_make.value);
  av_url += "&veh_model="        + encode_URI(av_form.veh_model.value);
  av_url += "&veh_onhand="       + encode_URI(av_form.veh_onhand.value);
  av_url += "&veh_offhand="      + encode_URI(av_form.veh_offhand.value);
  av_url += "&veh_interval="     + encode_URI(av_form.veh_interval.value);
  av_url += "&veh_rate="         + encode_URI(av_form.veh_rate.value);
  av_url += "&veh_onspeed="      + encode_URI(av_form.veh_onspeed.value);
  av_url += "&veh_offspeed="     + encode_URI(av_form.veh_offspeed.value);
  av_url += "&veh_onacc="        + encode_URI(av_form.veh_onacc.value);
  av_url += "&veh_offacc="       + encode_URI(av_form.veh_offacc.value);
  av_url += "&veh_pilot="        + encode_URI(av_form.veh_pilot.value);
  av_url += "&veh_body="         + encode_URI(av_form.veh_body.value);
  av_url += "&veh_armor="        + encode_URI(av_form.veh_armor.value);
  av_url += "&veh_sensor="       + encode_URI(av_form.veh_sensor.value);
  av_url += "&veh_sig="          + encode_URI(av_form.veh_sig.value);
  av_url += "&veh_nav="          + encode_URI(av_form.veh_nav.value);
  av_url += "&veh_cargo="        + encode_URI(av_form.veh_cargo.value);
  av_url += "&veh_load="         + encode_URI(av_form.veh_load.value);
  av_url += "&veh_hardpoints="   + encode_URI(av_form.veh_hardpoints.value);
  av_url += "&veh_firmpoints="   + encode_URI(av_form.veh_firmpoints.value);
  av_url += "&veh_onseats="      + encode_URI(av_form.veh_onseats.value);
  av_url += "&veh_offseats="     + encode_URI(av_form.veh_offseats.value);
  av_url += "&veh_avail="        + encode_URI(av_form.veh_avail.value);
  av_url += "&veh_perm="         + encode_URI(av_form.veh_perm.value);
  av_url += "&veh_basetime="     + encode_URI(av_form.veh_basetime.value);
  av_url += "&veh_duration="     + encode_URI(av_form.veh_duration.value);
  av_url += "&veh_index="        + encode_URI(av_form.veh_index.value);
  av_url += "&veh_cost="         + encode_URI(av_form.veh_cost.value);
  av_url += "&veh_book="         + encode_URI(av_form.veh_book.value);
  av_url += "&veh_page="         + encode_URI(av_form.veh_page.value);

  script = document.createElement('script');
  script.src = p_script_url + av_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.vehicles.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickVehicle' ).click(function() {
    $( "#dialogVehicle" ).dialog('open');
  });

  $( "#dialogVehicle" ).dialog({
    autoOpen: false,

    modal: true,
    height: 725,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogVehicle" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_vehicles('add.vehicles.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Vehicle",
        click: function() {
          attach_vehicles('add.vehicles.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Vehicle",
        click: function() {
          attach_vehicles('add.vehicles.mysql.php', 0);
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

<form name="vehicles">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Vehicles Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('vehicles-help');">Help</a></th>
</tr>
</table>

<div id="vehicles-help" style="display: none">

<div class="main-help ui-widget-content">

<p><strong>Vehicles</strong></p>

<p>This section lets you enter the statistics for vehicles from various Shadowrun systems. There are a broad range of 
column headers and not all of them are relevant to every Shadowrun edition.</p>

<ul>
  <li>Total: This column lists the number of characters that have added this vehicle or drone to their character. Until this value reaches 0, you cannot delete this vehicle.</li>
  <li>
</ul>

<p><strong>6Th World</strong> - Page 198</p>

<p>All movement statistics are in Meters per Combat Round. A Combat Round is 3 seconds.</p>

<ul>
  <li>Handling is the value used when doing maneuvers</li>
  <li>Speed Interval. At this interval, remove 1 from your dice pool when making Handling tests</li>
  <li>Acceleration is maximum meters of acceleration per combaat round.</li>
  <li>Speed is the maximum speed per combat round of the vehicle.</li>
</ul>

</div>

</div>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickVehicle" value="Add Vehicle"></td>
</tr>
</table>

</form>


<div id="tabs">

<ul>
  <li><a href="#aircraft">Aircraft</a></li>
  <li><a href="#groundcraft">Groundcraft</a></li>
  <li><a href="#watercraft">Watercraft</a></li>
  <li><a href="#ldrones">Large Drones</a></li>
  <li><a href="#mdrones">Medium Drones</a></li>
  <li><a href="#sdrones">Small Drones</a></li>
  <li><a href="#idrones">Minidrones</a></li>
  <li><a href="#odrones">Microdrones</a></li>
</ul>


<div id="aircraft">

<span id="aircraft_table"><?php print wait_Process('Loading Aircraft...')?></span>

</div>


<div id="groundcraft">

<span id="groundcraft_table"><?php print wait_Process('Loading Groundcraft...')?></span>

</div>


<div id="watercraft">

<span id="watercraft_table"><?php print wait_Process('Loading Watercraft...')?></span>

</div>


<div id="ldrones">

<span id="ldrones_table"><?php print wait_Process('Loading Large Drones...')?></span>

</div>


<div id="mdrones">

<span id="mdrones_table"><?php print wait_Process('Loading Medium Drones...')?></span>

</div>


<div id="sdrones">

<span id="sdrones_table"><?php print wait_Process('Loading Small Drones...')?></span>

</div>


<div id="idrones">

<span id="idrones_table"><?php print wait_Process('Loading Minidrones...')?></span>

</div>


<div id="odrones">

<span id="odrones_table"><?php print wait_Process('Loading Microdrones...')?></span>

</div>


</div>

</div>



<div id="dialogVehicle" title="Vehicle Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.vehicles.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
