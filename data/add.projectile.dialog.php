<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Class <select name="proj_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Projectile\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="proj_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="proj_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Accuracy <input type="text" name="proj_acc" size="3"></td>
</tr>
</tr>
  <td class="ui-widget-content">Attack Rating <input type="text" name="proj_ar1" size="3">/<input type="text" name="proj_ar2" size="3">/<input type="text" name="proj_ar3" size="3">/<input type="text" name="proj_ar4" size="3">/<input type="text" name="proj_ar5" size="3"> (sr6)</td>
</tr>
<tr>
  <td class="ui-widget-content">Damage <input type="text" name="proj_damage" size="3"><input type="text" name="proj_type" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Strength? <input type="checkbox" name="proj_strength"></td>
</tr>
<tr>
  <td class="ui-widget-content">Armor Penetration (AP) <input type="text" name="proj_ap" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="proj_avail" size="3"><input type="text" name="proj_perm" size="3"> Base Time <input type="text" name="proj_basetime" size="6"> Duration <select name="proj_duration">
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
  <td class="ui-widget-content">Street Index <input type="text" name="proj_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="proj_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="proj_book">
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
</select> <input type="text" name="proj_page" size="3"></td>
</tr>
</table>
