<?php
# Script: add.vehicles.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.vehicles.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

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

  if (check_userlevel(1)) {
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
    height: 700,
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
<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="veh_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Vehicles\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Type <input type="text" name="veh_type" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Make <input type="text" name="veh_make" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Model <input type="text" name="veh_model" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Handling <input type="text" name="veh_onhand" size="3">/<input type="text" name="veh_offhand" size="3"> (sr1/sr2/sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Speed Interval<input type="text" name="veh_interval" size="3"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">Speed <input type="text" name="veh_onspeed" size="3">/<input type="text" name="veh_offspeed" size="3"> (sr1/sr2/sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Acceleration <input type="text" name="veh_onacc" size="3">/<input type="text" name="veh_offacc" size="3"> (sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Body <input type="text" name="veh_body" size="3"> (sr1/sr2/sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Armor <input type="text" name="veh_armor" size="3"> (sr1/sr2/sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Pilot <input type="text" name="veh_pilot" size="3"> (sr1/sr2/sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Sensor <input type="text" name="veh_sensor" size="3"> (sr3/sr4/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Signature <input type="text" name="veh_sig" size="3"> (sr1/sr2/sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Autonav <input type="text" name="veh_nav" size="3"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cargo <input type="text" name="veh_cargo" size="3"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Load <input type="text" name="veh_load" size="3"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Hardpoints <input type="text" name="veh_hardpoints" size="3"> (sr1/sr2)</td>
</tr>
<tr>
  <td class="ui-widget-content">Firmpoints <input type="text" name="veh_firmpoints" size="3"> (sr1/sr2)</td>
</tr>
<tr>
  <td class="ui-widget-content">Seats <input type="text" name="veh_onseats" size="3">/<input type="text" name="veh_offseats" size="3"> (sr3/sr5/sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">Avail <input type="text" name="veh_avail" size="3"><input type="text" name="veh_perm" size="3"> Base Time <input type="text" name="veh_basetime" size="6"> Duration <select name="veh_duration">
<option value="0">Unset</option>
<?php
  $q_string  = "select dur_id,dur_name ";
  $q_string .= "from duration ";
  $q_string .= "order by dur_id ";
  $q_duration = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_duration = mysql_fetch_array($q_duration)) {
    print "<option value=\"" . $a_duration['dur_id'] . "\">" . $a_duration['dur_name'] . "</option>\n";
  }
?>
</select> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Street Index <input type="text" name="veh_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="veh_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="veh_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select> <input type="text" name="veh_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
