<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Complex Form <input type="text" name="form_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Target <select name="form_target">
    <option value="0">Device</option>
    <option value="1">File</option>
    <option value="2">Persona</option>
    <option value="3">Self</option>
    <option value="4">Sprite</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Duration <select name="form_duration">
    <option value="0">Immediate</option>
    <option value="1">Permanent</option>
    <option value="2">Sustained</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Complex Form Level? <input type="checkbox" name="form_level"> (sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Fading Value <input type="text" name="form_fading" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="form_book">
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
</select>: <input type="text" name="form_page" size="3"></td>
</tr>
</table>
