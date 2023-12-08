<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="ammo_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on class.class_subjectid = subjects.sub_id ";
  $q_string .= "where sub_name = \"Ammunition\" ";
  $q_string .= "order by class_name ";
  $q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysqli_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="ammo_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rounds <input type="text" name="ammo_rounds" size="4"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="ammo_rating" size="4"></td>
</tr>
<tr>
  <td class="ui-widget-content">Damage Modifier <input type="text" name="ammo_mod" size="15"></td>
</tr>
<tr>
  <td class="ui-widget-content">Damage Modifier, Close <input type="text" name="ammo_close" size="15"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">Damage Modifier, Near <input type="text" name="ammo_near" size="15"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">Armor Penetration (AP) Modifier <input type="text" name="ammo_ap" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Blast <input type="text" name="ammo_blast" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Armor Used <input type="text" name="ammo_armor" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="ammo_avail" size="3"><input type="text" name="ammo_perm" size="3"> Base Time <input type="text" name="ammo_basetime" size="6"> Duration <select name="ammo_duration">
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
  <td class="ui-widget-content">Street Index <input type="text" name="ammo_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="ammo_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="ammo_book">
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
</select>: <input type="text" name="ammo_page" size="3"></td>
</tr>
</table>
