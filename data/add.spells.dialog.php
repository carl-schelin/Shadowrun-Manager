<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Group <select name="spell_group">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Spells\" ";
  $q_string .= "order by class_name ";
  $q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_class = mysqli_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="spell_name" size="25"></td>
</tr>
<tr>
  <td class="ui-widget-content">Class <input type="text" name="spell_class" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Type <input type="text" name="spell_type" size="8"></td>
</tr>
<tr>
  <td class="ui-widget-content">Test <input type="text" name="spell_test" size="8"></td>
</tr>
<tr>
  <td class="ui-widget-content">Range <input type="text" name="spell_range" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Damage <input type="text" name="spell_damage" size="8"></td>
</tr>
<tr>
  <td class="ui-widget-content">Duration <input type="text" name="spell_duration" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Force? <input type="checkbox" name="spell_force"></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Force / 2? <input type="checkbox" name="spell_half"></td>
</tr>
<tr>
  <td class="ui-widget-content">Drain <input type="text" name="spell_drain" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="spell_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="spell_page" size="4"></td>
</tr>
</table>
