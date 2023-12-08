<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="veh_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Vehicles\" ";
  $q_string .= "order by class_name ";
  $q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysqli_fetch_array($q_class)) {
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
  <td class="ui-widget-content">Speed Rate<input type="text" name="veh_rate" size="3"> (sr5)</td>
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
  <td class="ui-widget-content">Availability <input type="text" name="veh_avail" size="3"><input type="text" name="veh_perm" size="3"> Base Time <input type="text" name="veh_basetime" size="6"> Duration <select name="veh_duration">
<option value="0">Unset</option>
<?php
  $q_string  = "select dur_id,dur_name ";
  $q_string .= "from duration ";
  $q_string .= "order by dur_id ";
  $q_duration = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_duration = mysqli_fetch_array($q_duration)) {
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
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select> <input type="text" name="veh_page" size="3"></td>
</tr>
</table>
