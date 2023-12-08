<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="rit_name" size="25"></td>
</tr>
<tr>
  <td class="ui-widget-content">Anchor <input type="checkbox" name="rit_anchor"></td>
</tr>
<tr>
  <td class="ui-widget-content">Material Link <input type="checkbox" name="rit_link"></td>
</tr>
<tr>
  <td class="ui-widget-content">Minion <input type="checkbox" name="rit_minion"></td>
</tr>
<tr>
  <td class="ui-widget-content">Spell <input type="checkbox" name="rit_spell"></td>
</tr>
<tr>
  <td class="ui-widget-content">Spotter <input type="checkbox" name="rit_spotter"></td>
</tr>
<tr>
  <td class="ui-widget-content">Threshold <input type="text" name="rit_threshold" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Length of Time to Cast <input type="text" name="rit_length" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Duration of Ritual <input type="text" name="rit_duration" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="rit_book">
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
</select>: <input type="text" name="rit_page" size="4"></td>
</tr>
</table>
