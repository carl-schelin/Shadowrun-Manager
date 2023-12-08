<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Type <select name="acc_type">
<?php
  $q_string  = "select sub_id,sub_name ";
  $q_string .= "from subjects ";
  $q_string .= "order by sub_name ";
  $q_subjects = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_subjects = mysqli_fetch_array($q_subjects)) {
    print "<option value=\"" . $a_subjects['sub_id'] . "\">" . $a_subjects['sub_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Class <select name="acc_class">
<option value="0">Any Subheading</option>
<?php
  $q_string  = "select class_id,class_name,sub_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "order by sub_name,class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysqli_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">(" . $a_class['sub_name'] . ") " . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Accessory To <input type="text" name="acc_accessory" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Accessory Name <input type="text" name="acc_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Mount <input type="text" name="acc_mount" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence <input type="text" name="acc_essence" size="4"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="acc_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Capacity <input type="text" name="acc_capacity" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="acc_avail" size="3"><input type="text" name="acc_perm" size="3"> Base Time <input type="text" name="acc_basetime" size="6"> Duration <select name="acc_duration">
<option value="0">Unset</option>
<?php
  $q_string  = "select dur_id,dur_name ";
  $q_string .= "from duration ";
  $q_string .= "order by dur_id ";
  $q_duration = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_duration = mysqli_fetch_array($q_duration)) {
    print "<option value=\"" . $a_duration['dur_id'] . "\">" . $a_duration['dur_name'] . "</option>\n";
  }
?>
</select> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Street Index <input type="text" name="acc_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="acc_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="acc_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="acc_page" size="3"></td>
</tr>
</table>
