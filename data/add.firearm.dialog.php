<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="fa_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Firearms\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Name <input type="text" name="fa_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Accuracy <input type="text" name="fa_acc" size="3"> (sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Damage Value <input type="text" name="fa_damage" size="2"><input type="text" name="fa_type" size="2">(<input type="text" name="fa_flag" size="2">)</td>
</tr>
<tr>
  <td class="ui-widget-content">AP <input type="text" name="fa_ap" size="3"> (sr4a/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Mode <input type="text" name="fa_mode1" size="3">/<input type="text" name="fa_mode2" size="3">/<input type="text" name="fa_mode3" size="3"></td>
<tr>
</tr>
  <td class="ui-widget-content">Attack Rating <input type="text" name="fa_ar1" size="3">/<input type="text" name="fa_ar2" size="3">/<input type="text" name="fa_ar3" size="3">/<input type="text" name="fa_ar4" size="3">/<input type="text" name="fa_ar5" size="3"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">RC <input type="text" name="fa_rc" size="3">(<input type="text" name="fa_fullrc" size="3">) (sr4a/sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Ammo <input type="text" name="fa_ammo1" size="3">(<input type="text" name="fa_clip1" size="3">)/<input type="text" name="fa_ammo2" size="3">(<input type="text" name="fa_clip2" size="3">)</td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="fa_avail" size="3"><input type="text" name="fa_perm" size="3"> Base Time <input type="text" name="fa_basetime" size="6"> Duration <select name="fa_duration">
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
  <td class="ui-widget-content">Street Index <input type="text" name="fa_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="fa_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="fa_book">
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
</select>: <input type="text" name="fa_page" size="3"></td>
</tr>
</table>
