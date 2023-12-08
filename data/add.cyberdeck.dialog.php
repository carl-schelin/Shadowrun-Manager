<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Brand <input type="text" name="deck_brand" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Model <input type="text" name="deck_model" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="deck_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Attack <input type="text" name="deck_attack" size="3">/Sleaze <input type="text" name="deck_sleaze" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="deck_data" size="3">/Firewall <input type="text" name="deck_firewall" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Persona <input type="text" name="deck_persona" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Hardening <input type="text" name="deck_hardening" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Memory <input type="text" name="deck_memory" size="3">/Storage <input type="text" name="deck_storage" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Load <input type="text" name="deck_load" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">I/O <input type="text" name="deck_io" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Response Increase <input type="text" name="deck_response" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Number of Programs <input type="text" name="deck_programs" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Company ID <input type="text" name="deck_access" size="18"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="deck_avail" size="3"><input type="text" name="deck_perm" size="3"> Base Time <input type="text" name="deck_basetime" size="6"> Duration <select name="deck_duration">
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
</select> (sr2/sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Street Index <input type="text" name="deck_index" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="deck_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="deck_book">
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
</select>: <input type="text" name="deck_page" size="3"></td>
</tr>
</table>
